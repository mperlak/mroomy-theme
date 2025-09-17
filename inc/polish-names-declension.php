<?php
/**
 * Polish names declension helper
 *
 * @package Mroomy
 */

/**
 * Get Polish genitive form of a name (dopełniacz - dla kogo? czego?)
 *
 * @param string $name The name in nominative case
 * @return string The name in genitive case
 */
function mroomy_get_genitive_form( $name ) {
    // Dictionary of common Polish names with their genitive forms
    $declensions = array(
        // === MALE NAMES ===
        // Popular traditional names
        'Oliwier' => 'Oliwiera',
        'Antoni' => 'Antoniego',
        'Jan' => 'Jana',
        'Aleksander' => 'Aleksandra',
        'Franciszek' => 'Franciszka',
        'Jakub' => 'Jakuba',
        'Leon' => 'Leona',
        'Mikołaj' => 'Mikołaja',
        'Stanisław' => 'Stanisława',
        'Filip' => 'Filipa',
        'Wojciech' => 'Wojciecha',
        'Adam' => 'Adama',
        'Kacper' => 'Kacpra',
        'Marcel' => 'Marcela',
        'Wiktor' => 'Wiktora',
        'Piotr' => 'Piotra',
        'Michał' => 'Michała',
        'Mateusz' => 'Mateusza',
        'Bartosz' => 'Bartosza',
        'Tomasz' => 'Tomasza',
        'Paweł' => 'Pawła',
        'Kamil' => 'Kamila',
        'Gabriel' => 'Gabriela',
        'Dawid' => 'Dawida',
        'Szymon' => 'Szymona',
        'Hubert' => 'Huberta',
        'Tymon' => 'Tymona',
        'Igor' => 'Igora',
        'Alan' => 'Alana',
        'Oskar' => 'Oskara',
        'Bruno' => 'Bruna',
        'Krzysztof' => 'Krzysztofa',
        'Rafał' => 'Rafała',
        'Daniel' => 'Daniela',
        'Sebastian' => 'Sebastiana',
        'Łukasz' => 'Łukasza',
        'Marek' => 'Marka',
        'Marcin' => 'Marcina',
        'Dominik' => 'Dominika',
        'Patryk' => 'Patryka',
        'Maksymilian' => 'Maksymiliana',
        'Fabian' => 'Fabiana',
        'Ksawery' => 'Ksawerego',
        'Tadeusz' => 'Tadeusza',
        'Julian' => 'Juliana',
        'Ignacy' => 'Ignacego',
        'Miłosz' => 'Miłosza',
        'Nikodem' => 'Nikodema',
        'Borys' => 'Borysa',
        'Karol' => 'Karola',

        // Diminutive forms (zdrobnienia męskie)
        'Henio' => 'Henia',
        'Henek' => 'Henka',
        'Henryk' => 'Henryka',
        'Antek' => 'Antka',
        'Antoś' => 'Antosia',
        'Janek' => 'Janka',
        'Jaś' => 'Jasia',
        'Kubuś' => 'Kubusia',
        'Kuba' => 'Kuby',
        'Franio' => 'Frania',
        'Franek' => 'Franka',
        'Stasiek' => 'Staśka',
        'Staś' => 'Stasia',
        'Wojtek' => 'Wojtka',
        'Adaś' => 'Adasia',
        'Filipek' => 'Filipka',
        'Piotrek' => 'Piotrka',
        'Piotruś' => 'Piotrusia',
        'Michałek' => 'Michałka',
        'Misiek' => 'Miska',
        'Mateuszek' => 'Mateuszka',
        'Bartek' => 'Bartka',
        'Bartuś' => 'Bartusia',
        'Tomek' => 'Tomka',
        'Tomuś' => 'Tomusia',
        'Pawełek' => 'Pawełka',
        'Kamilek' => 'Kamilka',
        'Dawidek' => 'Dawidka',
        'Szymonek' => 'Szymonka',

        // === FEMALE NAMES ===
        // Popular traditional names
        'Zofia' => 'Zofii',
        'Zuzanna' => 'Zuzanny',
        'Hanna' => 'Hanny',
        'Julia' => 'Julii',
        'Maja' => 'Mai',
        'Lena' => 'Leny',
        'Alicja' => 'Alicji',
        'Pola' => 'Poli',
        'Emilia' => 'Emilii',
        'Oliwia' => 'Oliwii',
        'Laura' => 'Laury',
        'Maria' => 'Marii',
        'Amelia' => 'Amelii',
        'Antonina' => 'Antoniny',
        'Liliana' => 'Liliany',
        'Aleksandra' => 'Aleksandry',
        'Natalia' => 'Natalii',
        'Wiktoria' => 'Wiktorii',
        'Marcelina' => 'Marceliny',
        'Gabriela' => 'Gabrieli',
        'Helena' => 'Heleny',
        'Michalina' => 'Michaliny',
        'Anna' => 'Anny',
        'Katarzyna' => 'Katarzyny',
        'Małgorzata' => 'Małgorzaty',
        'Agnieszka' => 'Agnieszki',
        'Barbara' => 'Barbary',
        'Ewa' => 'Ewy',
        'Magdalena' => 'Magdaleny',
        'Joanna' => 'Joanny',
        'Monika' => 'Moniki',
        'Paulina' => 'Pauliny',
        'Dorota' => 'Doroty',
        'Karolina' => 'Karoliny',
        'Marta' => 'Marty',
        'Beata' => 'Beaty',
        'Klara' => 'Klary',
        'Nadia' => 'Nadii',
        'Nikola' => 'Nikoli',
        'Iga' => 'Igi',
        'Kinga' => 'Kingi',
        'Milena' => 'Mileny',
        'Nina' => 'Niny',
        'Klaudia' => 'Klaudii',
        'Patrycja' => 'Patrycji',
        'Izabela' => 'Izabeli',
        'Daria' => 'Darii',
        'Jagoda' => 'Jagody',
        'Dominika' => 'Dominiki',
        'Weronika' => 'Weroniki',

        // Diminutive forms (zdrobnienia żeńskie)
        'Zosia' => 'Zosi',
        'Zuzia' => 'Zuzi',
        'Hania' => 'Hani',
        'Julka' => 'Julki',
        'Majka' => 'Majki',
        'Lenka' => 'Lenki',
        'Ala' => 'Ali',
        'Alka' => 'Alki',
        'Polcia' => 'Polci',
        'Emilka' => 'Emilki',
        'Liwia' => 'Liwii',
        'Laurka' => 'Laurki',
        'Marysia' => 'Marysi',
        'Marylka' => 'Marylki',
        'Amelka' => 'Amelki',
        'Tosia' => 'Tosi',
        'Antosia' => 'Antosi',
        'Lila' => 'Lili',
        'Lilianka' => 'Lilianki',
        'Ola' => 'Oli',
        'Oleńka' => 'Oleńki',
        'Natalka' => 'Natalki',
        'Wiki' => 'Wiki',
        'Gabrysia' => 'Gabrysi',
        'Helenka' => 'Helenki',
        'Misia' => 'Misi',
        'Michalinką' => 'Michalinki',
        'Ania' => 'Ani',
        'Anka' => 'Anki',
        'Kasia' => 'Kasi',
        'Kaśka' => 'Kaśki',
        'Gosia' => 'Gosi',
        'Małgosia' => 'Małgosi',
        'Agusia' => 'Agusi',
        'Basia' => 'Basi',
        'Baśka' => 'Baśki',
        'Ewka' => 'Ewki',
        'Madzia' => 'Madzi',
        'Magda' => 'Magdy',
        'Asia' => 'Asi',
        'Joasia' => 'Joasi',

        // Names ending with -usz, -osz (less common)
        'Tymoteusz' => 'Tymoteusza',
        'Mateusz' => 'Mateusza',
        'Arkadiusz' => 'Arkadiusza',
        'Dariusz' => 'Dariusza',
        'Janusz' => 'Janusza',
        'Mariusz' => 'Mariusza',
        'Juliusz' => 'Juliusza',
    );

    // Check if we have a specific declension in our dictionary
    if ( isset( $declensions[$name] ) ) {
        return $declensions[$name];
    }

    // If not in dictionary, apply general rules
    return mroomy_apply_genitive_rules( $name );
}

/**
 * Apply general Polish genitive declension rules
 *
 * @param string $name The name to decline
 * @return string The name in genitive case
 */
function mroomy_apply_genitive_rules( $name ) {
    $last_char = mb_substr( $name, -1 );
    $last_two = mb_substr( $name, -2 );
    $last_three = mb_substr( $name, -3 );

    // Female names ending with -a
    if ( $last_char === 'a' ) {
        // Names ending with -ia, -ja → -ii, -ji
        if ( $last_two === 'ia' ) {
            return mb_substr( $name, 0, -1 ) . 'i';
        }
        elseif ( $last_two === 'ja' ) {
            return mb_substr( $name, 0, -1 ) . 'i';
        }
        // Names ending with -ka → -ki
        elseif ( $last_two === 'ka' ) {
            return mb_substr( $name, 0, -1 ) . 'i';
        }
        // Names ending with -ga → -gi
        elseif ( $last_two === 'ga' ) {
            return mb_substr( $name, 0, -1 ) . 'i';
        }
        // Names ending with -da → -dy
        elseif ( $last_two === 'da' ) {
            return mb_substr( $name, 0, -1 ) . 'y';
        }
        // Names ending with -ta → -ty
        elseif ( $last_two === 'ta' ) {
            return mb_substr( $name, 0, -1 ) . 'y';
        }
        // Names ending with -ła → -ły
        elseif ( $last_two === 'ła' ) {
            return mb_substr( $name, 0, -1 ) . 'y';
        }
        // Names ending with -na → -ny
        elseif ( $last_two === 'na' ) {
            return mb_substr( $name, 0, -1 ) . 'y';
        }
        // Names ending with -ra → -ry
        elseif ( $last_two === 'ra' ) {
            return mb_substr( $name, 0, -1 ) . 'y';
        }
        // Most other female names ending with -a → -y
        else {
            return mb_substr( $name, 0, -1 ) . 'y';
        }
    }
    // Male names ending with -o (like Henio, Bruno)
    elseif ( $last_char === 'o' ) {
        return mb_substr( $name, 0, -1 ) . 'a';
    }
    // Male names ending with -ek
    elseif ( $last_two === 'ek' ) {
        return mb_substr( $name, 0, -2 ) . 'ka';
    }
    // Male names ending with -eł
    elseif ( $last_two === 'eł' ) {
        return mb_substr( $name, 0, -2 ) . 'ła';
    }
    // Names ending with -usz, -osz
    elseif ( $last_three === 'usz' || $last_three === 'osz' ) {
        return $name . 'a';
    }
    // Names ending with -sz, -cz, -ż, -rz (but not -usz, -osz)
    elseif ( $last_two === 'sz' || $last_two === 'cz' ||
             $last_char === 'ż' || $last_two === 'rz' ) {
        return $name . 'a';
    }
    // Male names ending with -y (like Jerzy)
    elseif ( $last_char === 'y' ) {
        return mb_substr( $name, 0, -1 ) . 'ego';
    }
    // Male names ending with -i (like Antoni)
    elseif ( $last_char === 'i' ) {
        return mb_substr( $name, 0, -1 ) . 'iego';
    }
    // Male names ending with -ś, -ć, -ń
    elseif ( $last_char === 'ś' || $last_char === 'ć' || $last_char === 'ń' ) {
        return mb_substr( $name, 0, -1 ) . 'sia';
    }
    // Most male names ending with consonants → add 'a'
    else {
        // Check if the last consonant is soft
        $soft_consonants = array('l', 'j');
        if ( in_array( $last_char, $soft_consonants ) ) {
            return $name . 'a';
        }
        // Default for hard consonants
        return $name . 'a';
    }
}

/**
 * Process multiple names with declension
 *
 * @param string $names_string String with one or more names
 * @return string Declined names string
 */
function mroomy_decline_multiple_names( $names_string ) {
    // Check for special cases that shouldn't be declined
    $special_cases = array(
        'rodzeństwo', 'rodzeństwa', 'bliźnięta', 'bliźniąt',
        'dzieci', 'maluchów', 'przedszkolaków', 'uczniów'
    );

    $lower_string = mb_strtolower( $names_string );
    foreach ( $special_cases as $special ) {
        if ( strpos( $lower_string, $special ) !== false ) {
            return $names_string; // Return unchanged
        }
    }

    // Handle "Name1 i Name2" format
    if ( preg_match( '/^(.+?)\s+i\s+(.+?)$/', $names_string, $matches ) ) {
        $name1 = mroomy_get_genitive_form( trim( $matches[1] ) );
        $name2 = mroomy_get_genitive_form( trim( $matches[2] ) );
        return $name1 . ' i ' . $name2;
    }

    // Handle "Name1, Name2 i Name3" format
    if ( preg_match( '/^(.+?),\s*(.+?)\s+i\s+(.+?)$/', $names_string, $matches ) ) {
        $name1 = mroomy_get_genitive_form( trim( $matches[1] ) );
        $name2 = mroomy_get_genitive_form( trim( $matches[2] ) );
        $name3 = mroomy_get_genitive_form( trim( $matches[3] ) );
        return $name1 . ', ' . $name2 . ' i ' . $name3;
    }

    // Handle "Name1, Name2, Name3" format (enumeration with commas)
    if ( strpos( $names_string, ',' ) !== false ) {
        $names = explode( ',', $names_string );
        $declined_names = array_map( function( $name ) {
            return mroomy_get_genitive_form( trim( $name ) );
        }, $names );
        return implode( ', ', $declined_names );
    }

    // Single name or unrecognized format
    return mroomy_get_genitive_form( $names_string );
}

/**
 * Test function to verify declensions
 */
function mroomy_test_genitive_declensions() {
    $test_names = array(
        'Oliwier' => 'Oliwiera',
        'Henio' => 'Henia',
        'Zosia' => 'Zosi',
        'Julia' => 'Julii',
        'Maja' => 'Mai',
        'Antoni' => 'Antoniego',
        'Jaś' => 'Jasia',
        'Hania' => 'Hani',
        'Piotr' => 'Piotra',
        'Magdalena' => 'Magdaleny',
        'Kasia' => 'Kasi',
        'Tomek' => 'Tomka',
        'Ania' => 'Ani',
        'Bartek' => 'Bartka',
    );

    $results = array();
    foreach ( $test_names as $name => $expected ) {
        $actual = mroomy_get_genitive_form( $name );
        $results[] = sprintf(
            "%s → %s (expected: %s) %s",
            $name,
            $actual,
            $expected,
            ( $actual === $expected ) ? '✅' : '❌'
        );
    }

    return $results;
}