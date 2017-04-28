<?php

namespace AppBundle\Entity;

class RandomScientist
{
    /** @var array|string[] */
    private static $names = ['Бертран Рассел', 'Норберт Винер', 'Даниэль Каннеман', 'Альберт Эйнштейн',
        'Ричард Докинз', 'Людвиг Витгенштейн', 'Курт Гёдель', 'Никола Тесла', 'Стивен Хокинг',
        'Бенуа Мандельброт', 'Вернер Гейзенберг', 'Нильс Бор', 'Ричард Фейнман', 'Илья Пригожин',
        'Доктор Стрейнджлав', 'Ноам Хомский', 'Джон Нэш', 'Давид Гильберт', 'Чарльз Дарвин', 'Георг Кантор',
        'Александр Флеминг', 'Карл Поппер', 'Марвин Мински', 'Алан Тьюринг'];

    /** @return string */
    public static function name()
    {
        return self::$names[array_rand(self::$names)];
    }

    /** @return array|string[] */
    public static function all()
    {
        return self::$names;
    }
}
