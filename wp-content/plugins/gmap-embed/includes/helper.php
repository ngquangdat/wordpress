<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'gmap_embed_no_of_post' ) ) {
	/**
	 * To count number of total post for type wpgmapembed
	 *
	 * @return int
	 * @since 1.7.0
	 */
	function gmap_embed_no_of_post() {
		$count_posts = wp_count_posts( 'wpgmapembed' );

		return isset( $count_posts->draft ) ? $count_posts->draft : 0;

	}
}

if ( ! function_exists( '_wgm_can_add_new_map' ) ) {
	/**
	 * Is using premium version or not
	 *
	 * @return bool
	 * @since 1.5.0
	 */
	function _wgm_can_add_new_map() {
		return ( gmap_embed_no_of_post() == 0 || get_option( '_wgm_is_p_v', 'N' ) == 'Y' );
	}
}

if ( ! function_exists( '_wgm_is_premium' ) ) {
	/**
	 * Is using premium version or not
	 *
	 * @return bool
	 * @since 1.5.0
	 */
	function _wgm_is_premium() {
		return get_option( '_wgm_is_p_v', 'N' ) == 'Y';
	}
}

if ( ! function_exists( 'get_center_lat_lng_by_map_id' ) ) {
	/**
	 * Getting center lat lng by map id
	 *
	 * @param int $map_id
	 * @return string
	 * @since 1.7.0
	 */
	function get_center_lat_lng_by_map_id( $map_id = 0 ) {
		$wpgmap_center_lat_lng = get_post_meta( intval( $map_id ), 'wpgmap_center_lat_lng', true );
		$wpgmap_latlng         = get_post_meta( intval( $map_id ), 'wpgmap_latlng', true );
		if ( $wpgmap_center_lat_lng == '' || $wpgmap_center_lat_lng == null || strlen( $wpgmap_center_lat_lng ) == 0 ) {
			update_post_meta( intval( $map_id ), 'wpgmap_center_lat_lng', $wpgmap_latlng );
			$wpgmap_center_lat_lng = $wpgmap_latlng;
		}
		return $wpgmap_center_lat_lng;
	}
}

if ( ! function_exists( 'gmap_embed_get_languages' ) ) {
	/**
	 * Getting supported google map languages
	 *
	 * @return array
	 * @since 1.7.0
	 */
	function gmap_embed_get_languages() {
		return array(
			'af'     => 'AFRIKAANS',
			'sq'     => 'ALBANIAN',
			'am'     => 'AMHARIC',
			'ar'     => 'ARABIC',
			'hy'     => 'ARMENIAN',
			'az'     => 'AZERBAIJANI',
			'eu'     => 'BASQUE',
			'be'     => 'BELARUSIAN',
			'bn'     => 'BENGALI',
			'bs'     => 'BOSNIAN',
			'bg'     => 'BULGARIAN',
			'my'     => 'BURMESE',
			'ca'     => 'CATALAN',
			'zh'     => 'CHINESE',
			'zh-CN'  => 'CHINESE (SIMPLIFIED)',
			'zh-HK'  => 'CHINESE (HONG KONG)',
			'zh-TW'  => 'CHINESE (TRADITIONAL)',
			'hr'     => 'CROATIAN',
			'cs'     => 'CZECH',
			'da'     => 'DANISH',
			'nl'     => 'DUTCH',
			'en'     => 'ENGLISH',
			'en-AU'  => 'ENGLISH (AUSTRALIAN)',
			'en-GB'  => 'ENGLISH (GREAT BRITAIN)',
			'et'     => 'ESTONIAN',
			'fa'     => 'FARSI',
			'fi'     => 'FINNISH',
			'fil'    => 'FILIPINO',
			'fr'     => 'FRENCH',
			'fr-CA'  => 'FRENCH (CANADA)',
			'gl'     => 'GALICIAN',
			'ka'     => 'GEORGIAN',
			'de'     => 'GERMAN',
			'el'     => 'GREEK',
			'gu'     => 'GUJARATI',
			'iw'     => 'HEBREW',
			'hi'     => 'HINDI',
			'hu'     => 'HUNGARIAN',
			'is'     => 'ICELANDIC',
			'id'     => 'INDONESIAN',
			'it'     => 'ITALIAN',
			'ja'     => 'JAPANESE',
			'kn'     => 'KANNADA',
			'kk'     => 'KAZAKH',
			'km'     => 'KHMER',
			'ko'     => 'KOREAN',
			'ky'     => 'KYRGYZ',
			'lo'     => 'LAO',
			'lv'     => 'LATVIAN',
			'lt'     => 'LITHUANIAN',
			'mk'     => 'MACEDONIAN',
			'ms'     => 'MALAY',
			'ml'     => 'MALAYALAM',
			'mr'     => 'MARATHI',
			'mn'     => 'MONGOLIAN',
			'ne'     => 'NEPALI',
			'no'     => 'NORWEGIAN',
			'pl'     => 'POLISH',
			'pt'     => 'PORTUGUESE',
			'pt-BR'  => 'PORTUGUESE (BRAZIL)',
			'pt-PT'  => 'PORTUGUESE (PORTUGAL)',
			'pa'     => 'PUNJABI',
			'ro'     => 'ROMANIAN',
			'ru'     => 'RUSSIAN',
			'sr'     => 'SERBIAN',
			'si'     => 'SINHALESE',
			'sk'     => 'SLOVAK',
			'sl'     => 'SLOVENIAN',
			'es'     => 'SPANISH',
			'es-419' => 'SPANISH (LATIN AMERICA)',
			'sw'     => 'SWAHILI',
			'sv'     => 'SWEDISH',
			'ta'     => 'TAMIL',
			'te'     => 'TELUGU',
			'th'     => 'THAI',
			'tr'     => 'TURKISH',
			'uk'     => 'UKRAINIAN',
			'ur'     => 'URDU',
			'uz'     => 'UZBEK',
			'vi'     => 'VIETNAMESE',
			'zu'     => 'ZULU',
		);
	}
}

if ( ! function_exists( 'gmap_embed_get_regions' ) ) {
	/**
	 * Getting supported google map regions
	 *
	 * @return array
	 * @since 1.7.0
	 */
	function gmap_embed_get_regions() {
		 return array(
			 'AF' => 'Afghanistan',
			 'AX' => 'Åland Islands',
			 'AL' => 'Albania',
			 'DZ' => 'Algeria',
			 'AS' => 'American Samoa',
			 'AD' => 'Andorra',
			 'AO' => 'Angola',
			 'AI' => 'Anguilla',
			 'AQ' => 'Antarctica',
			 'AG' => 'Antigua and Barbuda',
			 'AR' => 'Argentina',
			 'AM' => 'Armenia',
			 'AW' => 'Aruba',
			 'AU' => 'Australia',
			 'AT' => 'Austria',
			 'AZ' => 'Azerbaijan',
			 'BS' => 'Bahamas',
			 'BH' => 'Bahrain',
			 'BD' => 'Bangladesh',
			 'BB' => 'Barbados',
			 'BY' => 'Belarus',
			 'BE' => 'Belgium',
			 'BZ' => 'Belize',
			 'BJ' => 'Benin',
			 'BM' => 'Bermuda',
			 'BT' => 'Bhutan',
			 'BO' => 'Bolivia, Plurinational State of',
			 'BQ' => 'Bonaire, Sint Eustatius and Saba',
			 'BA' => 'Bosnia and Herzegovina',
			 'BW' => 'Botswana',
			 'BV' => 'Bouvet Island',
			 'BR' => 'Brazil',
			 'IO' => 'British Indian Ocean Territory',
			 'BN' => 'Brunei Darussalam',
			 'BG' => 'Bulgaria',
			 'BF' => 'Burkina Faso',
			 'BI' => 'Burundi',
			 'KH' => 'Cambodia',
			 'CM' => 'Cameroon',
			 'CA' => 'Canada',
			 'CV' => 'Cape Verde',
			 'KY' => 'Cayman Islands',
			 'CF' => 'Central African Republic',
			 'TD' => 'Chad',
			 'CL' => 'Chile',
			 'CN' => 'China',
			 'CX' => 'Christmas Island',
			 'CC' => 'Cocos (Keeling) Islands',
			 'CO' => 'Colombia',
			 'KM' => 'Comoros',
			 'CG' => 'Congo',
			 'CD' => 'Congo, the Democratic Republic of the',
			 'CK' => 'Cook Islands',
			 'CR' => 'Costa Rica',
			 'CI' => "Côte d'Ivoire",
			 'HR' => 'Croatia',
			 'CU' => 'Cuba',
			 'CW' => 'Curaçao',
			 'CY' => 'Cyprus',
			 'CZ' => 'Czech Republic',
			 'DK' => 'Denmark',
			 'DJ' => 'Djibouti',
			 'DM' => 'Dominica',
			 'DO' => 'Dominican Republic',
			 'EC' => 'Ecuador',
			 'EG' => 'Egypt',
			 'SV' => 'El Salvador',
			 'GQ' => 'Equatorial Guinea',
			 'ER' => 'Eritrea',
			 'EE' => 'Estonia',
			 'ET' => 'Ethiopia',
			 'FK' => 'Falkland Islands (Malvinas)',
			 'FO' => 'Faroe Islands',
			 'FJ' => 'Fiji',
			 'FI' => 'Finland',
			 'FR' => 'France',
			 'GF' => 'French Guiana',
			 'PF' => 'French Polynesia',
			 'TF' => 'French Southern Territories',
			 'GA' => 'Gabon',
			 'GM' => 'Gambia',
			 'GE' => 'Georgia',
			 'DE' => 'Germany',
			 'GH' => 'Ghana',
			 'GI' => 'Gibraltar',
			 'GR' => 'Greece',
			 'GL' => 'Greenland',
			 'GD' => 'Grenada',
			 'GP' => 'Guadeloupe',
			 'GU' => 'Guam',
			 'GT' => 'Guatemala',
			 'GG' => 'Guernsey',
			 'GN' => 'Guinea',
			 'GW' => 'Guinea-Bissau',
			 'GY' => 'Guyana',
			 'HT' => 'Haiti',
			 'HM' => 'Heard Island and McDonald Mcdonald Islands',
			 'VA' => 'Holy See (Vatican City State)',
			 'HN' => 'Honduras',
			 'HK' => 'Hong Kong',
			 'HU' => 'Hungary',
			 'IS' => 'Iceland',
			 'IN' => 'India',
			 'ID' => 'Indonesia',
			 'IR' => 'Iran, Islamic Republic of',
			 'IQ' => 'Iraq',
			 'IE' => 'Ireland',
			 'IM' => 'Isle of Man',
			 'IL' => 'Israel',
			 'IT' => 'Italy',
			 'JM' => 'Jamaica',
			 'JP' => 'Japan',
			 'JE' => 'Jersey',
			 'JO' => 'Jordan',
			 'KZ' => 'Kazakhstan',
			 'KE' => 'Kenya',
			 'KI' => 'Kiribati',
			 'KP' => "Korea, Democratic People's Republic of",
			 'KR' => 'Korea, Republic of',
			 'KW' => 'Kuwait',
			 'KG' => 'Kyrgyzstan',
			 'LA' => "Lao People's Democratic Republic",
			 'LV' => 'Latvia',
			 'LB' => 'Lebanon',
			 'LS' => 'Lesotho',
			 'LR' => 'Liberia',
			 'LY' => 'Libya',
			 'LI' => 'Liechtenstein',
			 'LT' => 'Lithuania',
			 'LU' => 'Luxembourg',
			 'MO' => 'Macao',
			 'MK' => 'Macedonia, the Former Yugoslav Republic of',
			 'MG' => 'Madagascar',
			 'MW' => 'Malawi',
			 'MY' => 'Malaysia',
			 'MV' => 'Maldives',
			 'ML' => 'Mali',
			 'MT' => 'Malta',
			 'MH' => 'Marshall Islands',
			 'MQ' => 'Martinique',
			 'MR' => 'Mauritania',
			 'MU' => 'Mauritius',
			 'YT' => 'Mayotte',
			 'MX' => 'Mexico',
			 'FM' => 'Micronesia, Federated States of',
			 'MD' => 'Moldova, Republic of',
			 'MC' => 'Monaco',
			 'MN' => 'Mongolia',
			 'ME' => 'Montenegro',
			 'MS' => 'Montserrat',
			 'MA' => 'Morocco',
			 'MZ' => 'Mozambique',
			 'MM' => 'Myanmar',
			 'NA' => 'Namibia',
			 'NR' => 'Nauru',
			 'NP' => 'Nepal',
			 'NL' => 'Netherlands',
			 'NC' => 'New Caledonia',
			 'NZ' => 'New Zealand',
			 'NI' => 'Nicaragua',
			 'NE' => 'Niger',
			 'NG' => 'Nigeria',
			 'NU' => 'Niue',
			 'NF' => 'Norfolk Island',
			 'MP' => 'Northern Mariana Islands',
			 'NO' => 'Norway',
			 'OM' => 'Oman',
			 'PK' => 'Pakistan',
			 'PW' => 'Palau',
			 'PS' => 'Palestine, State of',
			 'PA' => 'Panama',
			 'PG' => 'Papua New Guinea',
			 'PY' => 'Paraguay',
			 'PE' => 'Peru',
			 'PH' => 'Philippines',
			 'PN' => 'Pitcairn',
			 'PL' => 'Poland',
			 'PT' => 'Portugal',
			 'PR' => 'Puerto Rico',
			 'QA' => 'Qatar',
			 'RE' => 'Réunion',
			 'RO' => 'Romania',
			 'RU' => 'Russian Federation',
			 'RW' => 'Rwanda',
			 'BL' => 'Saint Barthélemy',
			 'SH' => 'Saint Helena, Ascension and Tristan da Cunha',
			 'KN' => 'Saint Kitts and Nevis',
			 'LC' => 'Saint Lucia',
			 'MF' => 'Saint Martin (French part)',
			 'PM' => 'Saint Pierre and Miquelon',
			 'VC' => 'Saint Vincent and the Grenadines',
			 'WS' => 'Samoa',
			 'SM' => 'San Marino',
			 'ST' => 'Sao Tome and Principe',
			 'SA' => 'Saudi Arabia',
			 'SN' => 'Senegal',
			 'RS' => 'Serbia',
			 'SC' => 'Seychelles',
			 'SL' => 'Sierra Leone',
			 'SG' => 'Singapore',
			 'SX' => 'Sint Maarten (Dutch part)',
			 'SK' => 'Slovakia',
			 'SI' => 'Slovenia',
			 'SB' => 'Solomon Islands',
			 'SO' => 'Somalia',
			 'ZA' => 'South Africa',
			 'GS' => 'South Georgia and the South Sandwich Islands',
			 'SS' => 'South Sudan',
			 'ES' => 'Spain',
			 'LK' => 'Sri Lanka',
			 'SD' => 'Sudan',
			 'SR' => 'Suriname',
			 'SJ' => 'Svalbard and Jan Mayen',
			 'SZ' => 'Swaziland',
			 'SE' => 'Sweden',
			 'CH' => 'Switzerland',
			 'SY' => 'Syrian Arab Republic',
			 'TW' => 'Taiwan',
			 'TJ' => 'Tajikistan',
			 'TZ' => 'Tanzania, United Republic of',
			 'TH' => 'Thailand',
			 'TL' => 'Timor-Leste',
			 'TG' => 'Togo',
			 'TK' => 'Tokelau',
			 'TO' => 'Tonga',
			 'TT' => 'Trinidad and Tobago',
			 'TN' => 'Tunisia',
			 'TR' => 'Turkey',
			 'TM' => 'Turkmenistan',
			 'TC' => 'Turks and Caicos Islands',
			 'TV' => 'Tuvalu',
			 'UG' => 'Uganda',
			 'UA' => 'Ukraine',
			 'AE' => 'United Arab Emirates',
			 'GB' => 'United Kingdom',
			 'US' => 'United States',
			 'UM' => 'United States Minor Outlying Islands',
			 'UY' => 'Uruguay',
			 'UZ' => 'Uzbekistan',
			 'VU' => 'Vanuatu',
			 'VE' => 'Venezuela, Bolivarian Republic of',
			 'VN' => 'Vietnam',
			 'VG' => 'Virgin Islands, British',
			 'VI' => 'Virgin Islands, U.S.',
			 'WF' => 'Wallis and Futuna',
			 'EH' => 'Western Sahara',
			 'YE' => 'Yemen',
			 'ZM' => 'Zambia',
			 'ZW' => 'Zimbabwe',
		 );
	}
}
