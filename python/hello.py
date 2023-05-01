#! /usr/bin/env python
# -*- coding: utf-8 -*-

import random
import os

#os.system('/Applications/MAMP/bin/php/php7.4.33/bin/php -f /Users/sbushinskii/workspace/today/service/today.php')

kakoy_arr = [
    'жуликоватый',
    'шаловливый',
    'лысый',
    'ехидный',
    'усатый',
    'брехливый',
    'неугомонный',
    'озверевщий',
    'смердящий',
    'лохматый',
    'вонючий',
    'сумасшедший',
    'супер жирный',
    'уродливый',
]

kto_arr = [
    'Буржуй',
    'Говноежка',
    'Пухляк',
    'Жиробас',
    'Плакса',
    'Бабиджон',
    'Толстожоп',
    'Вонючка',
    'Свин',
    'Грязнопоп',
    'Егор',
    'Мурзик',
]

gde_arr = [
    'на горшке',
    'в лесу',
    'из цирка',
    'из школы',
    'со шрамом',
    'на мопеде',
    'на свинье',
    'без тормозов',
    'без трусов',
    'из огорода',
    'в шапке',
    'из унитаза',
    'с соплями',
    'на горшке',
    'у бармоглота в гостях',
    'с ёршиком',
    'в детском саду',
    'с аладушками и с бабушкой',
    'без носков',
    'из магазина с газировкай',
]

print("Привет, кто ты, путник?")
visitors = []
for iterator in range(0,3):
    kakoi = random.choice(list(kakoy_arr))
    who = random.choice(list(kto_arr))
    otkuda = random.choice(list(gde_arr))
    username = kakoi + ' ' + who + ' ' + otkuda
    visitors.append((username))
    print( str(iterator) + ' - ' + username)

txt = int(input("Я: "))
age = int(input("Сколько тебе лет? "))

if age < 10 :
    age_text = 'Малыш'
else:
    if age < 25:
        age_text = 'Молодой человек'
    else:
        if age < 45:
            age_text = 'Мужчина'
        else:
            if age < 65:
                age_text = 'Дедушка'
            else:
                if age >= 65:
                    age_text = 'Мумия'


print("Привет, " + age_text + ", " + str(visitors[txt]))