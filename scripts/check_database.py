"""Analyze llms.txt entries for potential errors or unnecessary items."""
from __future__ import annotations

import re
from collections import Counter, defaultdict
from dataclasses import dataclass
from pathlib import Path
from typing import Dict, Iterable, List

BASE_DIR = Path(__file__).resolve().parent.parent
DATA_FILE = BASE_DIR / "llms.txt"
REPORT_FILE = BASE_DIR / "reports" / "database_audit.md"

ERROR_KEYWORDS = {
    "error",
    "failed",
    "invalid",
    "not found",
    "unavailable",
}

UNNECESSARY_KEYWORDS = {
    "demo",
    "test",
    "placeholder",
    "sample",
    "dummy",
    "lorem",
    "lipsum",
}

SENSITIVE_KEYWORDS = {
    "password protected",
    "private",
}

ENTRY_PATTERN = re.compile(
    r"- \[(?P<title>[^\]]+)\]\((?P<url>[^)]+)\)(?:: (?P<description>.*))?",
    re.IGNORECASE,
)


@dataclass
class Entry:
    section: str
    title: str
    url: str
    description: str

    def to_dict(self) -> Dict[str, str]:
        return {
            "section": self.section,
            "title": self.title,
            "url": self.url,
            "description": self.description,
        }


class DatabaseAudit:
    def __init__(self, entries: Iterable[Entry]):
        self.entries: List[Entry] = list(entries)
        self._title_counts = Counter(entry.title.lower() for entry in self.entries)
        self._url_counts = Counter(entry.url.lower() for entry in self.entries)

    def _flag_by_keywords(self, keywords: Iterable[str]) -> List[Dict[str, str]]:
        flags: List[Dict[str, str]] = []
        for entry in self.entries:
            haystack = " ".join([entry.title, entry.description]).lower()
            for keyword in keywords:
                if keyword in haystack:
                    flags.append({
                        "section": entry.section,
                        "title": entry.title,
                        "url": entry.url,
                        "description": entry.description,
                        "note": f"Contains keyword '{keyword}'",
                    })
                    break
        return flags

    def _flag_duplicates(self, counts: Counter) -> List[Dict[str, str]]:
        duplicates: List[Dict[str, str]] = []
        for entry in self.entries:
            key = entry.title.lower()
            if counts[key] > 1:
                duplicates.append({
                    "section": entry.section,
                    "title": entry.title,
                    "url": entry.url,
                    "description": entry.description,
                    "note": f"Duplicate title occurs {counts[key]} times",
                })
        return duplicates

    def _flag_duplicate_urls(self) -> List[Dict[str, str]]:
        duplicates: List[Dict[str, str]] = []
        for entry in self.entries:
            key = entry.url.lower()
            if self._url_counts[key] > 1:
                duplicates.append({
                    "section": entry.section,
                    "title": entry.title,
                    "url": entry.url,
                    "description": entry.description,
                    "note": f"Duplicate URL occurs {self._url_counts[key]} times",
                })
        return duplicates

    def generate_report(self) -> str:
        sections = defaultdict(list)
        for entry in self.entries:
            sections[entry.section].append(entry)

        error_flags = self._flag_by_keywords(ERROR_KEYWORDS)
        unnecessary_flags = self._flag_by_keywords(UNNECESSARY_KEYWORDS)
        sensitive_flags = self._flag_by_keywords(SENSITIVE_KEYWORDS)
        duplicate_title_flags = self._flag_duplicates(self._title_counts)
        duplicate_url_flags = self._flag_duplicate_urls()

        report_lines = [
            "# Database Audit Report",
            "",
            f"Source file: `{DATA_FILE.relative_to(BASE_DIR)}`",
            "",
            f"Total entries analysed: {len(self.entries)}",
            "",
            "## Entries by section",
        ]

        for section_name, entries in sorted(sections.items()):
            report_lines.append(f"- {section_name}: {len(entries)} entries")
        report_lines.append("")

        def add_table(title: str, rows: List[Dict[str, str]]):
            report_lines.append(f"## {title}")
            if not rows:
                report_lines.append("No issues detected.\n")
                return
            report_lines.append("| Section | Title | Notes | URL |")
            report_lines.append("| --- | --- | --- | --- |")
            for row in rows:
                notes = row.get("note", "")
                report_lines.append(
                    f"| {row['section']} | {row['title']} | {notes} | {row['url']} |"
                )
            report_lines.append("")

        add_table("Potential error indicators", error_flags)
        add_table("Potentially unnecessary entries", unnecessary_flags)
        add_table("Sensitive or restricted entries", sensitive_flags)
        add_table("Duplicate titles", duplicate_title_flags)
        add_table("Duplicate URLs", duplicate_url_flags)

        return "\n".join(report_lines).rstrip() + "\n"


def parse_entries(text: str) -> List[Entry]:
    entries: List[Entry] = []
    current_section = "Unknown"
    for raw_line in text.splitlines():
        line = raw_line.strip()
        if not line:
            continue
        if line.startswith("## "):
            current_section = line[3:].strip()
            continue
        match = ENTRY_PATTERN.match(line)
        if match:
            entries.append(
                Entry(
                    section=current_section,
                    title=match.group("title").strip(),
                    url=match.group("url").strip(),
                    description=(match.group("description") or "").strip(),
                )
            )
    return entries


def main() -> None:
    if not DATA_FILE.exists():
        raise FileNotFoundError(f"Data file not found: {DATA_FILE}")

    content = DATA_FILE.read_text(encoding="utf-8")
    entries = parse_entries(content)
    audit = DatabaseAudit(entries)
    report = audit.generate_report()
    REPORT_FILE.write_text(report, encoding="utf-8")
    print(f"Audit complete. Report written to {REPORT_FILE}")


if __name__ == "__main__":
    main()
