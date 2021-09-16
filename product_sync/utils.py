import operator

def out(*args, in_production = False,**kwargs): None if in_production else print(*args, **kwargs)

def Convert(tup):
    '''gets raw procurement_cursor_fetchall and sum duplicates to be one
        eg. data = [('hwintia', 10, 40.0), ('fom wisa', 20, 50.0), ('fom wisa', 20, 70.0), ('hwintia', 60, 40.0)]
        converted_data will be = [('hwintia', 70, 80.0), ('fom wisa', 40, 120.0))
    '''
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