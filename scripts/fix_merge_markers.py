#!/usr/bin/env python
from pathlib import Path
import re

root = Path(__file__).resolve().parent.parent / 'resources' / 'views'

# First resolve full conflict blocks by keeping the HEAD side.
conflict_re = re.compile(r'<<<<<<< HEAD\s*\n(.*?)\n=======\s*\n(.*?)\n>>>>>>>[^\n]*\n', re.S)

# Then remove any remaining stray conflict marker lines that may have been left behind.
marker_line_re = re.compile(r'^(?:<<<<<<<|=======|>>>>>>>).*\n', re.M)

fixed_files = []
for p in root.rglob('*.blade.php'):
    text = p.read_text(encoding='utf-8')
    new_text = conflict_re.sub(lambda m: m.group(1), text)
    new_text = marker_line_re.sub('', new_text)
    if new_text != text:
        p.write_text(new_text, encoding='utf-8')
        fixed_files.append(str(p))

print('Fixed', len(fixed_files), 'files')
for f in fixed_files:
    print(' -', f)
