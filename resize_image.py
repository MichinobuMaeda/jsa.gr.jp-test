import sys
from PIL import Image

im = Image.open(sys.argv[1])
print(im.format, im.size, im.mode)
size = (300, round(im.size[1] * 300 / im.size[0]))
im.thumbnail(size)
im.save("jjs-cover-s.jpg", "JPEG")
print(size)
