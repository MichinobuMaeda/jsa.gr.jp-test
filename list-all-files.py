import os

def list_file(root):
    ignore_first_level = ('d', 'pukiwiki')

    for (dirpath, _, filenames) in os.walk(root):
        path = os.path.relpath(dirpath, root)
        if path and path.split(os.sep)[0] in ignore_first_level: continue

        for filename in filenames:
            if filename.startswith('.'): continue
            yield (path, filename)

if __name__ == '__main__':
    for (path, file) in sorted(
        list_file(
            os.path.join(os.path.dirname(__file__), 'www', 'zenkoku')
        )
    ):
        print(os.path.join(path, file))
