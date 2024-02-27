from hilbertcurve.hilbertcurve import HilbertCurve
import sys
import math

# https://pydefis.callicode.fr/defis/C23_Hilbert/txt

data = open(sys.argv[1]).read().strip()
r = [list(ligne) for ligne in data.split('\n')]
r.reverse()

p = int(math.log2(len(r)))
n = 2
hilbert_curve = HilbertCurve(p, n)
distances = list(range(len(r) * len(r)))
points = hilbert_curve.points_from_distances(distances)
start = ''
end = ''
for point, dist in zip(points, distances):
    if dist % 2 == 0:
        start += r[point[1]][point[0]]
    else:
        end += r[point[1]][point[0]]

message = start + end[::-1]
print(message)
