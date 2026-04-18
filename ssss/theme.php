<?php
header("Content-Type: application/javascript");

include_once "../admin/services/database.php";

// Função para buscar o número do tema do banco de dados
function getThemeNumber()
{
    global $mysqli;
    $qry = "SELECT tema FROM config WHERE id = 1";
    $result = mysqli_query($mysqli, $qry);
    $row = mysqli_fetch_assoc($result);
    return $row['tema'] ?? null;
}

$themeNumber = getThemeNumber();

// Lista de temas
$themeNames = [
    1 => 'black',
    2 => 'blue',
    3 => 'whiteGreen',
    4 => 'purple',
    5 => 'whiteRed',
    6 => 'oilyGreen',
    7 => 'versaceYellow',
    8 => 'lancomePeach',
    9 => 'whiteYellow',
    10 => 'whiteBlue',
    11 => 'sk2',
    12 => 'whiteOrange',
    13 => 'hermesOrange',
    14 => 'lightBrown',
    15 => 'furlaBlue',
    16 => 'whitePink',
    17 => 'bvGreen',
    18 => 'whiteBrown',
    19 => 'AnnaSuiPurple',
    20 => 'whitePurple',
    21 => 'burgundyRed',
    22 => 'whiteDarkGreen',
    23 => 'greenGold',
    24 => 'whiteBrownLauren',
    25 => 'whiteRedGucci',
    26 => 'embraerBlue',
    27 => 'elsaPink',
    28 => 'bvlgariBrown',
    29 => 'whiteBlack',
    30 => 'whiteBlueFendi',
    31 => 'venetaGrey',
    32 => 'martinPurple',
    33 => 'usdtGreen',
    34 => 'whiteGreenCindy',
    35 => 'microsoftRed',
    36 => 'celineBrownWhite',
    37 => 'ferrariBlack',
    38 => 'burberryBlueWhite',
    39 => 'windbellGreen',
    40 => 'diorPurpleWhite',
    41 => 'almondYellow',
    42 => 'montblancBluewhite',
    43 => 'bondiBlue',
    44 => 'wasabiYellowWhite',
    45 => 'gemRed',
    46 => 'coralPurpleWhite',
    47 => 'elegantPinkWhite',
    48 => 'lemonGreenWhite',
    49 => 'caramelYelloWhite',
    50 => 'camelliaRedWhite',
    51 => 'coralPurple'
];

// Verificar se o tema foi encontrado e definir um tema padrão caso não seja
$theme = $themeNames[$themeNumber] ?? 'defaultTheme';

// Gerar o código JavaScript que será executado
echo "
(function updateThemeColor() {
    var theme = '{$theme}';
    document.documentElement.setAttribute('theme', theme);
    var pc ='1'
    document.documentElement.setAttribute('pc', pc);
})();
";
