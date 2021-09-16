import operator
from time import sleep

# data = [('hwintia', 10, 40.0), ('fom wisa', 20, 50.0),
#         ('fom wisa', 20, 70.0), ('hwintia', 60, 40.0)]

def Convert(tup):
    di={}
    # print('di', di)
    for a, b, c in tup:
        di.setdefault(a, []).append((b, c))
    for x, value in di.items():
        final = (0,0)
        for y in value:
            final = tuple(map(operator.add, final, y))
            di[x] = final
    di = [(k, v[0], v[1]) for k, v in di.items()]
    return di

while True:
    sleep(3)

    d = Convert([('hwintia', 10, 40.0), ('fom wisa', 20, 50.0), ('fom wisa', 20, 70.0), ('hwintia', 60, 40.0)])
    print(d)
