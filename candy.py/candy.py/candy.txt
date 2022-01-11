'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
'' This program finds all occurrences of the search word in the N*N square 
''   in accordance with the set task.
'' Applied classic recursive approach and pattern of labyrinth with 
''   some changes. First, a representation of the text
''   is copied to a two-dimensional array. After that, all the vertices 
''   are located.
''   Next, a simplified algorithm is run 
''   recursion, no object model and no data on the reverse. It's a little bit
''   makes it harder to transfer search results, but simplifies the program.
'' The method itself with recursive processing can be delegated to workers
''   (use Python Multiprocess Workers/Queue), since the find_candy function 
''   is a bottleneck with many identical tasks,
''   with mutually isolated data.
'' The Python language does not allow declaring multidimensional arrays 
''   without extensions, therefore, a list with coordinates 
''   is used to find the route, but string (scalar) - to find the literal.
'' Additional comments in the text are almost unnecessary in mind 
''   the obviousness of the program code.
''
'' Эта программа находит все вхождения искомого слова в поле N*N 
''   в соответствии с поставленным заданием.
''   Применён классический рекурсивный подход и паттерн лабиринта с 
''   некоторыми изменениями. Сперва строчное представление текста
''   копируется в двумерный массив. После этого находятся все вершины
''   деревьев для запуска. Далее запускается упрощенный алгоритм 
''   рекурсии, без объектной модели и без данных на реверсе. Это немного
''   усложняет передачу результатов поиска, но упрощает программу.
''   Сам метод с рекурсивной обработкой можно перепоручить рабочим процессам
''   (использовать Многопроцессный рабочий процесс/очередь Python),
''   так как функция find_candy - узкое место с множеством идентичных задач,
''   с взаимо изолированными данными.
'' Язык Питон не позволяет без расширений объявлять многомерные массивы,  
''   поэтому используется список с координатами для поиска маршрута,
''   но строка (скалар) - для поиска литерала.
'' Дополнительные комментарии в тексте практически не нужны в виду 
''   очевидности кода программы.
''
'' MIT License (c) 2021 Cheva <dmytro.cheusov@outlook.com>
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
import math #to use math.sqrt

# Without OOP model class we need to use global variables instead of properties
debug = 1  # Add debug in results output
do_ask = 1 # Do ask or use defaults
candy = '' # Search word
box = ''   # Input stream
size = 0   # Square edge


def get_numeric(v):
# Input checker for integers with default value
    while True:
        response = input()
        if response == '': response = v
        try:
            return int(response)
        except ValueError:
            print("Please enter a number: ", end=" ")


if do_ask:
# Input dialogue
    print("Enter word to find in the box or press ENTER to use default 'rafaello':", end=" ")
    candy = str(input())
    if len(candy) == 0:
        candy = "rafaello"

    while True:
        print("Enter string of the box or press ENTER to use default 8x8:", end=" ")
        box = str(input())
        if len(box) == 0:
            box = "ykadzlleaimtqorapqbevaqfrapncqwjzfsdfjpkguijdyoislleafzhzexceare"
            '''
            [0,0]ykadzlle[0,7]
            [1,0]aimtqora[1,7]
            [2,0]pqbevaqf[2,7]
            [3,0]rapncqwj[3,7]
            [4,0]zfsdfjpk[4,7]
            [5,0]guijdyoi[5,7]
            [6,0]slleafzh[6,7]
            [7,0]zexceare[7,7]
            '''
        size = int(math.sqrt(len(box)))
        size_ = math.sqrt(len(box))
        print(f"You entered {len(box)} chars.")
        if size==size_: 
            print(f"Square square with edge {size} will be used.")
            break
        else: print("Please enter the text with the length that has a natural square root (4, 9, 16, 25, 36, 49, 64, 81, 100 and so on)")

    # protect from errors (index out of range)
    while size*size < len(box):
        print(f"Enter sqrt size of the box EQUAL or LESS THAN {size} or press ENTER to use default {size}:", end=" ")
        isize = get_numeric(size)
        if isize<size: continue #asking
    
    print("Enable test output? ([yY]es or [nN]o), ENTER to enable:", end=" ")
    no = str(input())
    if no != '' and (no[0] == 'n' or no[0] == 'N'): debug = False

else:
    candy = "rafaello"
    box = "ykadzlleaimtqorapqbevaqfrapncqwjzfsdfjpkguijdyoislleafzhzexceare"
    size = 8

# Try to use bigger box
# candy = "rafaellorafaello"
# size = 16
# box = "ykexevaqfropncqwjzfsdfjpzexcearemtqodfjprglijdyoisladzlleaimtqaiqwrzfsdfaelguijdyraevaqfrypncqwjzfadfjokfulorafaellofzhzexclleazrafqbelkguijdykancqwjzfseareyklldzoleelfzhzexeeaorafaellohaqoryeykldzleafaellorapyoislleafzllefaealeafarlorafaelaqfrypncyreeafar"
# '''
# ykqbevaqfropncqwjzfsdfjpzexceare
# mtqodfjprglijdyoisladzlleaimtqai
# qwrzfsdfaelguijdyraevaqfrypncqwj
# zfadfjokfulorafaellofzhzexclleaz
# rafqbelkguijdykancqwjzfseareykll
# dzoleelfzhzexceaorafaellohaqorye
# ykldzleafaellorapyoislleafzllefa
# ealeafarlorafaelaqfrypncyreeafar
# '''

searched = ""
recursed = 0
sprint = []
sfound = []


def box_as_arr():
# This function copies string data to a list that acts as a two-dimensional array with a width 'size'
    i = 0
    j = 0
    box_arr = []
    for b in box:
        box_arr.append([b, [i, j]])
        j += 1
        if (j >= size):
            i += 1
            j = 0

    return box_arr


def find_first(box_arr):
#Find the first nodes
    entrys = []
    for b in box_arr:
        if (b[0] == candy[0]):
            entrys.append(b[1])

    return entrys


def possible_paths(point):
# Find 4 available routes around the current node.
    paths = [[point[0] - 1, point[1]],
             [point[0], point[1] - 1],
             [point[0], point[1] + 1],
             [point[0] + 1, point[1]]]
    possible_paths = []

    for path in paths:
        if path[0] >= 0 and path[1] >= 0 and path[0] <= size - 1 and path[1] <= size - 1:
            possible_paths.append(path)

    return possible_paths


def find_candy(point, step=0, start_path=[], full_path=[]):
# Main recursion to execute here or migrate to queue workers
    global searched
    global recursed
    global sprint
    global sfound
    step += 1
    sid = 0

    if start_path == []:
        sid = point[0] * size + point[1]
        sprint.append([sid, f"{point}"])
        start_path = [point]
        full_path = []

    if sid == 0: sid = start_path[0][0] * size + start_path[0][1]

    paths = [path for path in possible_paths(point)]

    if (step == len(candy)):
        ind = point[0] * size + point[1]
        if candy[step - 1] == box[ind]:
            return True  # word found

    else:
        for path in paths:
            ind = path[0] * size + path[1]
            searched = searched + str(ind) + ":" + box[ind] + "[" + str(path[0]) + ":" + str(path[1]) + "] "
            recursed += 1
            if box[ind] == candy[step]:
                full_path.append(point)
                sprint.append([sid, f"{path}"])
                res = find_candy(path, step, start_path, full_path)
                if res:
                    sfound.append(sid)


def printr(sprint):
    sid_ = -1
    dm = ''
    sf = 0
    for s in sprint:
        sid = s[0]
        node = s[1]
        if sid in sfound:
            sf = sfound[sfound.index(sid)]

        if sid != (sf):
            dm = f"visited for {sid}:{box[sid]} \t"
        else: dm = ''

        if debug or sid == (sf):
            if sid != sid_:
                print(f"\n{dm}{node}", end="")
                sid_ = sid
            else:
                print(f"->{node}", end="")

    if sfound == []: print("\n\nNot found!")


# begin
entrys = find_first(box_as_arr())
for entry in entrys:
    find_candy(entry)
printr(sprint)
# debug info
if debug:
    print("\n--------")
    print(" Candy:\t\t" + candy + " (" + str(len(candy)) + ")")
    print(f" Box: \t\t{box} ({size}x{size}) ")
    print(" Roots:\t\t", end="")
    print(f"{entrys}")
    print("--------")
    print(" Visited: \t" + str(recursed) + "\n " + searched + "\n")
