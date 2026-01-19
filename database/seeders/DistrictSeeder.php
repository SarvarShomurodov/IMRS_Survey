<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\District;
use App\Models\Region;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        // Region ID larni olish
        $regions = Region::pluck('id', 'code')->toArray();

        $districts = [
            // Qoraqalpog'iston Respublikasi
            ['region_code' => 'QR', 'name_uz' => 'Amudaryo tumani', 'name_ru' => 'Амударё тумани', 'code' => 'QR001'],
            ['region_code' => 'QR', 'name_uz' => 'Beruniy tumani', 'name_ru' => 'Беруний тумани', 'code' => 'QR002'],
            ['region_code' => 'QR', 'name_uz' => 'Qo\'ng\'irot tumani', 'name_ru' => 'Қўнғирот тумани', 'code' => 'QR003'],
            ['region_code' => 'QR', 'name_uz' => 'Mo\'ynoq tumani', 'name_ru' => 'Мўйноқ тумани', 'code' => 'QR004'],
            ['region_code' => 'QR', 'name_uz' => 'Nukus tumani', 'name_ru' => 'Нукус тумани', 'code' => 'QR005'],
            ['region_code' => 'QR', 'name_uz' => 'Qorao\'zak tumani', 'name_ru' => 'Қораўзак тумани', 'code' => 'QR006'],
            ['region_code' => 'QR', 'name_uz' => 'Qanliko\'l tumani', 'name_ru' => 'Қанликўл тумани', 'code' => 'QR007'],
            ['region_code' => 'QR', 'name_uz' => 'Shumanay tumani', 'name_ru' => 'Шуманай тумани', 'code' => 'QR008'],
            ['region_code' => 'QR', 'name_uz' => 'Taxtako\'pir tumani', 'name_ru' => 'Тахтакўпир тумани', 'code' => 'QR009'],
            ['region_code' => 'QR', 'name_uz' => 'To\'rtko\'l tumani', 'name_ru' => 'Тўрткўл тумани', 'code' => 'QR010'],
            ['region_code' => 'QR', 'name_uz' => 'Xo\'jayli tumani', 'name_ru' => 'Хўжайли тумани', 'code' => 'QR011'],
            ['region_code' => 'QR', 'name_uz' => 'Chimboy tumani', 'name_ru' => 'Чимбой тумани', 'code' => 'QR012'],
            ['region_code' => 'QR', 'name_uz' => 'Sho\'rko\'l tumani', 'name_ru' => 'Шўркўл тумани', 'code' => 'QR013'],
            ['region_code' => 'QR', 'name_uz' => 'Ellikqala tumani', 'name_ru' => 'Елликқала тумани', 'code' => 'QR014'],
            ['region_code' => 'QR', 'name_uz' => 'Nukus shahri', 'name_ru' => 'Нукус шаҳри', 'code' => 'QR015'],

            // Andijon viloyati
            ['region_code' => 'AN', 'name_uz' => 'Andijon tumani', 'name_ru' => 'Андижон тумани', 'code' => 'AN001'],
            ['region_code' => 'AN', 'name_uz' => 'Asaka tumani', 'name_ru' => 'Асака тумани', 'code' => 'AN002'],
            ['region_code' => 'AN', 'name_uz' => 'Baliqchi tumani', 'name_ru' => 'Балиқчи тумани', 'code' => 'AN003'],
            ['region_code' => 'AN', 'name_uz' => 'Bo\'z tumani', 'name_ru' => 'Бўз тумани', 'code' => 'AN004'],
            ['region_code' => 'AN', 'name_uz' => 'Buloqboshi tumani', 'name_ru' => 'Булоқбоши тумани', 'code' => 'AN005'],
            ['region_code' => 'AN', 'name_uz' => 'Izboskan tumani', 'name_ru' => 'Избоскан тумани', 'code' => 'AN006'],
            ['region_code' => 'AN', 'name_uz' => 'Jalaquduq tumani', 'name_ru' => 'Жалақудуқ тумани', 'code' => 'AN007'],
            ['region_code' => 'AN', 'name_uz' => 'Xo\'jaobod tumani', 'name_ru' => 'Хўжаобод тумани', 'code' => 'AN008'],
            ['region_code' => 'AN', 'name_uz' => 'Qo\'rg\'ontepa tumani', 'name_ru' => 'Қўрғонтепа тумани', 'code' => 'AN009'],
            ['region_code' => 'AN', 'name_uz' => 'Marhamat tumani', 'name_ru' => 'Марҳамат тумани', 'code' => 'AN010'],
            ['region_code' => 'AN', 'name_uz' => 'Oltinko\'l tumani', 'name_ru' => 'Олтинкўл тумани', 'code' => 'AN011'],
            ['region_code' => 'AN', 'name_uz' => 'Paxtaobod tumani', 'name_ru' => 'Пахтаобод тумани', 'code' => 'AN012'],
            ['region_code' => 'AN', 'name_uz' => 'Shahrixon tumani', 'name_ru' => 'Шаҳрихон тумани', 'code' => 'AN013'],
            ['region_code' => 'AN', 'name_uz' => 'Ulug\'nor tumani', 'name_ru' => 'Улуғнор тумани', 'code' => 'AN014'],
            ['region_code' => 'AN', 'name_uz' => 'Andijon shahri', 'name_ru' => 'Андижон шаҳри', 'code' => 'AN015'],

            // Buxoro viloyati
            ['region_code' => 'BU', 'name_uz' => 'Buxoro tumani', 'name_ru' => 'Бухоро тумани', 'code' => 'BU001'],
            ['region_code' => 'BU', 'name_uz' => 'Vobkent tumani', 'name_ru' => 'Вобкент тумани', 'code' => 'BU002'],
            ['region_code' => 'BU', 'name_uz' => 'G\'ijduvon tumani', 'name_ru' => 'Ғиждувон тумани', 'code' => 'BU003'],
            ['region_code' => 'BU', 'name_uz' => 'Jondor tumani', 'name_ru' => 'Жондор тумани', 'code' => 'BU004'],
            ['region_code' => 'BU', 'name_uz' => 'Kogon tumani', 'name_ru' => 'Когон тумани', 'code' => 'BU005'],
            ['region_code' => 'BU', 'name_uz' => 'Qorako\'l tumani', 'name_ru' => 'Қоракўл тумани', 'code' => 'BU006'],
            ['region_code' => 'BU', 'name_uz' => 'Qorovulbozor tumani', 'name_ru' => 'Қоровулбозор тумани', 'code' => 'BU007'],
            ['region_code' => 'BU', 'name_uz' => 'Olot tumani', 'name_ru' => 'Олот тумани', 'code' => 'BU008'],
            ['region_code' => 'BU', 'name_uz' => 'Peshku tumani', 'name_ru' => 'Пешку тумани', 'code' => 'BU009'],
            ['region_code' => 'BU', 'name_uz' => 'Romitan tumani', 'name_ru' => 'Ромитан тумани', 'code' => 'BU010'],
            ['region_code' => 'BU', 'name_uz' => 'Shofirkon tumani', 'name_ru' => 'Шофиркон тумани', 'code' => 'BU011'],
            ['region_code' => 'BU', 'name_uz' => 'Buxoro shahri', 'name_ru' => 'Бухоро шаҳри', 'code' => 'BU012'],

            // Jizzax viloyati
            ['region_code' => 'JI', 'name_uz' => 'Arnasoy tumani', 'name_ru' => 'Арнасой тумани', 'code' => 'JI001'],
            ['region_code' => 'JI', 'name_uz' => 'Baxtiyor tumani', 'name_ru' => 'Бахтийор тумани', 'code' => 'JI002'],
            ['region_code' => 'JI', 'name_uz' => 'Do\'stlik tumani', 'name_ru' => 'Дўстлик тумани', 'code' => 'JI003'],
            ['region_code' => 'JI', 'name_uz' => 'Forish tumani', 'name_ru' => 'Фориш тумани', 'code' => 'JI004'],
            ['region_code' => 'JI', 'name_uz' => 'G\'allaorol tumani', 'name_ru' => 'Ғаллаорол тумани', 'code' => 'JI005'],
            ['region_code' => 'JI', 'name_uz' => 'Jizzax tumani', 'name_ru' => 'Жиззах тумани', 'code' => 'JI006'],
            ['region_code' => 'JI', 'name_uz' => 'Mirzacho\'l tumani', 'name_ru' => 'Мирзачўл тумани', 'code' => 'JI007'],
            ['region_code' => 'JI', 'name_uz' => 'Paxtakor tumani', 'name_ru' => 'Пахтакор тумани', 'code' => 'JI008'],
            ['region_code' => 'JI', 'name_uz' => 'Yangiobod tumani', 'name_ru' => 'Янгиобод тумани', 'code' => 'JI009'],
            ['region_code' => 'JI', 'name_uz' => 'Zafarobod tumani', 'name_ru' => 'Зафаробод тумани', 'code' => 'JI010'],
            ['region_code' => 'JI', 'name_uz' => 'Zarbdor tumani', 'name_ru' => 'Зарбдор тумани', 'code' => 'JI011'],
            ['region_code' => 'JI', 'name_uz' => 'Zaynobiddin tumani', 'name_ru' => 'Зайнобиддин тумани', 'code' => 'JI012'],
            ['region_code' => 'JI', 'name_uz' => 'Jizzax shahri', 'name_ru' => 'Жиззах шаҳри', 'code' => 'JI013'],

            // Qashqadaryo viloyati
            ['region_code' => 'QA', 'name_uz' => 'Chiroqchi tumani', 'name_ru' => 'Чироқчи тумани', 'code' => 'QA001'],
            ['region_code' => 'QA', 'name_uz' => 'Dehqonobod tumani', 'name_ru' => 'Деҳқонобод тумани', 'code' => 'QA002'],
            ['region_code' => 'QA', 'name_uz' => 'G\'uzor tumani', 'name_ru' => 'Ғузор тумани', 'code' => 'QA003'],
            ['region_code' => 'QA', 'name_uz' => 'Qamashi tumani', 'name_ru' => 'Қамаши тумани', 'code' => 'QA004'],
            ['region_code' => 'QA', 'name_uz' => 'Qarshi tumani', 'name_ru' => 'Қарши тумани', 'code' => 'QA005'],
            ['region_code' => 'QA', 'name_uz' => 'Kasbi tumani', 'name_ru' => 'Касби тумани', 'code' => 'QA006'],
            ['region_code' => 'QA', 'name_uz' => 'Kitob tumani', 'name_ru' => 'Китоб тумани', 'code' => 'QA007'],
            ['region_code' => 'QA', 'name_uz' => 'Koson tumani', 'name_ru' => 'Косон тумани', 'code' => 'QA008'],
            ['region_code' => 'QA', 'name_uz' => 'Mirishkor tumani', 'name_ru' => 'Миришкор тумани', 'code' => 'QA009'],
            ['region_code' => 'QA', 'name_uz' => 'Muborak tumani', 'name_ru' => 'Муборак тумани', 'code' => 'QA010'],
            ['region_code' => 'QA', 'name_uz' => 'Nishon tumani', 'name_ru' => 'Нишон тумани', 'code' => 'QA011'],
            ['region_code' => 'QA', 'name_uz' => 'Shahrisabz tumani', 'name_ru' => 'Шаҳрисабз тумани', 'code' => 'QA012'],
            ['region_code' => 'QA', 'name_uz' => 'Yakkabog\' tumani', 'name_ru' => 'Яккабоғ тумани', 'code' => 'QA013'],
            ['region_code' => 'QA', 'name_uz' => 'Qarshi shahri', 'name_ru' => 'Қарши шаҳри', 'code' => 'QA014'],

            // Navoiy viloyati
            ['region_code' => 'NV', 'name_uz' => 'Kanimex tumani', 'name_ru' => 'Канимех тумани', 'code' => 'NV001'],
            ['region_code' => 'NV', 'name_uz' => 'Karmana tumani', 'name_ru' => 'Кармана тумани', 'code' => 'NV002'],
            ['region_code' => 'NV', 'name_uz' => 'Qiziltepa tumani', 'name_ru' => 'Қизилтепа тумани', 'code' => 'NV003'],
            ['region_code' => 'NV', 'name_uz' => 'Konimex tumani', 'name_ru' => 'Конимех тумани', 'code' => 'NV004'],
            ['region_code' => 'NV', 'name_uz' => 'Navbahor tumani', 'name_ru' => 'Навбаҳор тумани', 'code' => 'NV005'],
            ['region_code' => 'NV', 'name_uz' => 'Navoiy tumani', 'name_ru' => 'Навоий тумани', 'code' => 'NV006'],
            ['region_code' => 'NV', 'name_uz' => 'Nurota tumani', 'name_ru' => 'Нурота тумани', 'code' => 'NV007'],
            ['region_code' => 'NV', 'name_uz' => 'Tomdi tumani', 'name_ru' => 'Томди тумани', 'code' => 'NV008'],
            ['region_code' => 'NV', 'name_uz' => 'Uchquduq tumani', 'name_ru' => 'Учқудуқ тумани', 'code' => 'NV009'],
            ['region_code' => 'NV', 'name_uz' => 'Xatirchi tumani', 'name_ru' => 'Хатирчи тумани', 'code' => 'NV010'],
            ['region_code' => 'NV', 'name_uz' => 'Navoiy shahri', 'name_ru' => 'Навоий шаҳри', 'code' => 'NV011'],

            // Namangan viloyati
            ['region_code' => 'NM', 'name_uz' => 'Chortoq tumani', 'name_ru' => 'Чортоқ тумани', 'code' => 'NM001'],
            ['region_code' => 'NM', 'name_uz' => 'Chust tumani', 'name_ru' => 'Чуст тумани', 'code' => 'NM002'],
            ['region_code' => 'NM', 'name_uz' => 'Kosonsoy tumani', 'name_ru' => 'Косонсой тумани', 'code' => 'NM003'],
            ['region_code' => 'NM', 'name_uz' => 'Mingbuloq tumani', 'name_ru' => 'Мингбулоқ тумани', 'code' => 'NM004'],
            ['region_code' => 'NM', 'name_uz' => 'Namangan tumani', 'name_ru' => 'Наманган тумани', 'code' => 'NM005'],
            ['region_code' => 'NM', 'name_uz' => 'Norin tumani', 'name_ru' => 'Норин тумани', 'code' => 'NM006'],
            ['region_code' => 'NM', 'name_uz' => 'Pop tumani', 'name_ru' => 'Поп тумани', 'code' => 'NM007'],
            ['region_code' => 'NM', 'name_uz' => 'To\'raqo\'rg\'on tumani', 'name_ru' => 'Тўрақўрғон тумани', 'code' => 'NM008'],
            ['region_code' => 'NM', 'name_uz' => 'Uchqo\'rg\'on tumani', 'name_ru' => 'Учқўрғон тумани', 'code' => 'NM009'],
            ['region_code' => 'NM', 'name_uz' => 'Uychi tumani', 'name_ru' => 'Уйчи тумани', 'code' => 'NM010'],
            ['region_code' => 'NM', 'name_uz' => 'Yangiqo\'rg\'on tumani', 'name_ru' => 'Янгиқўрғон тумани', 'code' => 'NM011'],
            ['region_code' => 'NM', 'name_uz' => 'Namangan shahri', 'name_ru' => 'Наманган шаҳри', 'code' => 'NM012'],

            // Samarqand viloyati
            ['region_code' => 'SM', 'name_uz' => 'Bulungur tumani', 'name_ru' => 'Булунғур тумани', 'code' => 'SM001'],
            ['region_code' => 'SM', 'name_uz' => 'Ishtixon tumani', 'name_ru' => 'Иштихон тумани', 'code' => 'SM002'],
            ['region_code' => 'SM', 'name_uz' => 'Jomboy tumani', 'name_ru' => 'Жомбой тумани', 'code' => 'SM003'],
            ['region_code' => 'SM', 'name_uz' => 'Kattaqo\'rg\'on tumani', 'name_ru' => 'Каттақўрғон тумани', 'code' => 'SM004'],
            ['region_code' => 'SM', 'name_uz' => 'Narpay tumani', 'name_ru' => 'Нарпай тумани', 'code' => 'SM005'],
            ['region_code' => 'SM', 'name_uz' => 'Nurobod tumani', 'name_ru' => 'Нуробод тумани', 'code' => 'SM006'],
            ['region_code' => 'SM', 'name_uz' => 'Oqdaryo tumani', 'name_ru' => 'Оқдарё тумани', 'code' => 'SM007'],
            ['region_code' => 'SM', 'name_uz' => 'Pastdarg\'om tumani', 'name_ru' => 'Пастдарғом тумани', 'code' => 'SM008'],
            ['region_code' => 'SM', 'name_uz' => 'Payariq tumani', 'name_ru' => 'Пайариқ тумани', 'code' => 'SM009'],
            ['region_code' => 'SM', 'name_uz' => 'Qo\'shrabot tumani', 'name_ru' => 'Қўшработ тумани', 'code' => 'SM010'],
            ['region_code' => 'SM', 'name_uz' => 'Samarqand tumani', 'name_ru' => 'Самарқанд тумани', 'code' => 'SM011'],
            ['region_code' => 'SM', 'name_uz' => 'Toyloq tumani', 'name_ru' => 'Тойлоқ тумани', 'code' => 'SM012'],
            ['region_code' => 'SM', 'name_uz' => 'Urgut tumani', 'name_ru' => 'Урғут тумани', 'code' => 'SM013'],
            ['region_code' => 'SM', 'name_uz' => 'Samarqand shahri', 'name_ru' => 'Самарқанд шаҳри', 'code' => 'SM014'],
            ['region_code' => 'SM', 'name_uz' => 'Taxtachi tumani', 'name_ru' => 'Тахтачи тумани', 'code' => 'SM015'],

            // Surxondaryo viloyati
            ['region_code' => 'SU', 'name_uz' => 'Angor tumani', 'name_ru' => 'Ангор тумани', 'code' => 'SU001'],
            ['region_code' => 'SU', 'name_uz' => 'Boysun tumani', 'name_ru' => 'Бойсун тумани', 'code' => 'SU002'],
            ['region_code' => 'SU', 'name_uz' => 'Denov tumani', 'name_ru' => 'Денов тумани', 'code' => 'SU003'],
            ['region_code' => 'SU', 'name_uz' => 'Jarqo\'rg\'on tumani', 'name_ru' => 'Жарқўрғон тумани', 'code' => 'SU004'],
            ['region_code' => 'SU', 'name_uz' => 'Qiziriq tumani', 'name_ru' => 'Қизириқ тумани', 'code' => 'SU005'],
            ['region_code' => 'SU', 'name_uz' => 'Qumqo\'rg\'on tumani', 'name_ru' => 'Қумқўрғон тумани', 'code' => 'SU006'],
            ['region_code' => 'SU', 'name_uz' => 'Muzrabot tumani', 'name_ru' => 'Музработ тумани', 'code' => 'SU007'],
            ['region_code' => 'SU', 'name_uz' => 'Oltinsoy tumani', 'name_ru' => 'Олтинсой тумани', 'code' => 'SU008'],
            ['region_code' => 'SU', 'name_uz' => 'Sario\'siyoyev tumani', 'name_ru' => 'Сариосиё тумани', 'code' => 'SU009'],
            ['region_code' => 'SU', 'name_uz' => 'Sherobod tumani', 'name_ru' => 'Шеробод тумани', 'code' => 'SU010'],
            ['region_code' => 'SU', 'name_uz' => 'Sho\'rchi tumani', 'name_ru' => 'Шўрчи тумани', 'code' => 'SU011'],
            ['region_code' => 'SU', 'name_uz' => 'Termiz tumani', 'name_ru' => 'Термиз тумани', 'code' => 'SU012'],
            ['region_code' => 'SU', 'name_uz' => 'Uzun tumani', 'name_ru' => 'Узун тумани', 'code' => 'SU013'],
            ['region_code' => 'SU', 'name_uz' => 'Termiz shahri', 'name_ru' => 'Термиз шаҳри', 'code' => 'SU014'],

            // Sirdaryo viloyati
            ['region_code' => 'SI', 'name_uz' => 'Boyovut tumani', 'name_ru' => 'Боёвут тумани', 'code' => 'SI001'],
            ['region_code' => 'SI', 'name_uz' => 'Guliston tumani', 'name_ru' => 'Гулистон тумани', 'code' => 'SI002'],
            ['region_code' => 'SI', 'name_uz' => 'Xavos tumani', 'name_ru' => 'Хавос тумани', 'code' => 'SI003'],
            ['region_code' => 'SI', 'name_uz' => 'Mirzaobod tumani', 'name_ru' => 'Мирзаобод тумани', 'code' => 'SI004'],
            ['region_code' => 'SI', 'name_uz' => 'Oqoltin tumani', 'name_ru' => 'Оқолтин тумани', 'code' => 'SI005'],
            ['region_code' => 'SI', 'name_uz' => 'Sardoba tumani', 'name_ru' => 'Сардоба тумани', 'code' => 'SI006'],
            ['region_code' => 'SI', 'name_uz' => 'Sayxunobod tumani', 'name_ru' => 'Сайхунобод тумани', 'code' => 'SI007'],
            ['region_code' => 'SI', 'name_uz' => 'Sirdaryo tumani', 'name_ru' => 'Сирдарё тумани', 'code' => 'SI008'],
            ['region_code' => 'SI', 'name_uz' => 'Guliston shahri', 'name_ru' => 'Гулистон шаҳри', 'code' => 'SI009'],

            // Toshkent viloyati
            ['region_code' => 'TO', 'name_uz' => 'Bekobod tumani', 'name_ru' => 'Бекобод тумани', 'code' => 'TO001'],
            ['region_code' => 'TO', 'name_uz' => 'Bo\'stonliq tumani', 'name_ru' => 'Бўстонлиқ тумани', 'code' => 'TO002'],
            ['region_code' => 'TO', 'name_uz' => 'Bo\'ka tumani', 'name_ru' => 'Бўка тумани', 'code' => 'TO003'],
            ['region_code' => 'TO', 'name_uz' => 'Chinoz tumani', 'name_ru' => 'Чиноз тумани', 'code' => 'TO004'],
            ['region_code' => 'TO', 'name_uz' => 'Qibray tumani', 'name_ru' => 'Қибрай тумани', 'code' => 'TO005'],
            ['region_code' => 'TO', 'name_uz' => 'Ohangarqn tumani', 'name_ru' => 'Оҳангарон тумани', 'code' => 'TO006'],
            ['region_code' => 'TO', 'name_uz' => 'Olmaliq tumani', 'name_ru' => 'Олмалиқ тумани', 'code' => 'TO007'],
            ['region_code' => 'TO', 'name_uz' => 'Oqqo\'rg\'on tumani', 'name_ru' => 'Оққўрғон тумани', 'code' => 'TO008'],
            ['region_code' => 'TO', 'name_uz' => 'Parkent tumani', 'name_ru' => 'Паркент тумани', 'code' => 'TO009'],
            ['region_code' => 'TO', 'name_uz' => 'Piskent tumani', 'name_ru' => 'Пискент тумани', 'code' => 'TO010'],
            ['region_code' => 'TO', 'name_uz' => 'Quyichirchiq tumani', 'name_ru' => 'Қуйичирчиқ тумани', 'code' => 'TO011'],
            ['region_code' => 'TO', 'name_uz' => 'Toshkent tumani', 'name_ru' => 'Тошкент тумани', 'code' => 'TO012'],
            ['region_code' => 'TO', 'name_uz' => 'O\'rta Chirchiq tumani', 'name_ru' => 'Ўрта Чирчиқ тумани', 'code' => 'TO013'],
            ['region_code' => 'TO', 'name_uz' => 'Yuqorichirchiq tumani', 'name_ru' => 'Юқоричирчиқ тумани', 'code' => 'TO014'],
            ['region_code' => 'TO', 'name_uz' => 'Zangiota tumani', 'name_ru' => 'Зангиота тумани', 'code' => 'TO015'],

            // Farg'ona viloyati
            ['region_code' => 'FA', 'name_uz' => 'Beshariq tumani', 'name_ru' => 'Бешариқ тумани', 'code' => 'FA001'],
            ['region_code' => 'FA', 'name_uz' => 'Bog\'dod tumani', 'name_ru' => 'Боғдод тумани', 'code' => 'FA002'],
            ['region_code' => 'FA', 'name_uz' => 'Buvayda tumani', 'name_ru' => 'Бувайда тумани', 'code' => 'FA003'],
            ['region_code' => 'FA', 'name_uz' => 'Dang\'ara tumani', 'name_ru' => 'Данғара тумани', 'code' => 'FA004'],
            ['region_code' => 'FA', 'name_uz' => 'Farg\'ona tumani', 'name_ru' => 'Фарғона тумани', 'code' => 'FA005'],
            ['region_code' => 'FA', 'name_uz' => 'Furqat tumani', 'name_ru' => 'Фурқат тумани', 'code' => 'FA006'],
            ['region_code' => 'FA', 'name_uz' => 'O\'zbekiston tumani', 'name_ru' => 'Ўзбекистон тумани', 'code' => 'FA007'],
            ['region_code' => 'FA', 'name_uz' => 'Qo\'shtepa tumani', 'name_ru' => 'Қўштепа тумани', 'code' => 'FA008'],
            ['region_code' => 'FA', 'name_uz' => 'Quva tumani', 'name_ru' => 'Қува тумани', 'code' => 'FA009'],
            ['region_code' => 'FA', 'name_uz' => 'Rishton tumani', 'name_ru' => 'Риштон тумани', 'code' => 'FA010'],
            ['region_code' => 'FA', 'name_uz' => 'So\'x tumani', 'name_ru' => 'Сўх тумани', 'code' => 'FA011'],
            ['region_code' => 'FA', 'name_uz' => 'Toshloq tumani', 'name_ru' => 'Тошлоқ тумани', 'code' => 'FA012'],
            ['region_code' => 'FA', 'name_uz' => 'Uchko\'prik tumani', 'name_ru' => 'Учкўприк тумани', 'code' => 'FA013'],
            ['region_code' => 'FA', 'name_uz' => 'Yozyovon tumani', 'name_ru' => 'Ёзёвон тумани', 'code' => 'FA014'],
            ['region_code' => 'FA', 'name_uz' => 'Farg\'ona shahri', 'name_ru' => 'Фарғона шаҳри', 'code' => 'FA015'],

            // Xorazm viloyati
            ['region_code' => 'XO', 'name_uz' => 'Bog\'ot tumani', 'name_ru' => 'Боғот тумани', 'code' => 'XO001'],
            ['region_code' => 'XO', 'name_uz' => 'Gurlen tumani', 'name_ru' => 'Гурлен тумани', 'code' => 'XO002'],
            ['region_code' => 'XO', 'name_uz' => 'Xonqa tumani', 'name_ru' => 'Хонқа тумани', 'code' => 'XO003'],
            ['region_code' => 'XO', 'name_uz' => 'Hazorasp tumani', 'name_ru' => 'Ҳазорасп тумани', 'code' => 'XO004'],
            ['region_code' => 'XO', 'name_uz' => 'Xiva tumani', 'name_ru' => 'Хива тумани', 'code' => 'XO005'],
            ['region_code' => 'XO', 'name_uz' => 'Qo\'shko\'pir tumani', 'name_ru' => 'Қўшкўпир тумани', 'code' => 'XO006'],
            ['region_code' => 'XO', 'name_uz' => 'Shovot tumani', 'name_ru' => 'Шовот тумани', 'code' => 'XO007'],
            ['region_code' => 'XO', 'name_uz' => 'Tuproqqala tumani', 'name_ru' => 'Тупроққала тумани', 'code' => 'XO008'],
            ['region_code' => 'XO', 'name_uz' => 'Urganch tumani', 'name_ru' => 'Урганч тумани', 'code' => 'XO009'],
            ['region_code' => 'XO', 'name_uz' => 'Yangiariq tumani', 'name_ru' => 'Янгиариқ тумани', 'code' => 'XO010'],
            ['region_code' => 'XO', 'name_uz' => 'Yangibozor tumani', 'name_ru' => 'Янгибозор тумани', 'code' => 'XO011'],
            ['region_code' => 'XO', 'name_uz' => 'Urganch shahri', 'name_ru' => 'Урганч шаҳри', 'code' => 'XO012'],

            // Toshkent shahri
            ['region_code' => 'TS', 'name_uz' => 'Bektemir tumani', 'name_ru' => 'Бектемир тумани', 'code' => 'TS001'],
            ['region_code' => 'TS', 'name_uz' => 'Chilonzor tumani', 'name_ru' => 'Чилонзор тумани', 'code' => 'TS002'],
            ['region_code' => 'TS', 'name_uz' => 'Mirobod tumani', 'name_ru' => 'Миробод тумани', 'code' => 'TS003'],
            ['region_code' => 'TS', 'name_uz' => 'Mirzo Ulug\'bek tumani', 'name_ru' => 'Мирзо Улуғбек тумани', 'code' => 'TS004'],
            ['region_code' => 'TS', 'name_uz' => 'Olmazar tumani', 'name_ru' => 'Олмазор тумани', 'code' => 'TS005'],
            ['region_code' => 'TS', 'name_uz' => 'Sergeli tumani', 'name_ru' => 'Сергели тумани', 'code' => 'TS006'],
            ['region_code' => 'TS', 'name_uz' => 'Shayhontohur tumani', 'name_ru' => 'Шайхонтоҳур тумани', 'code' => 'TS007'],
            ['region_code' => 'TS', 'name_uz' => 'Uchtepa tumani', 'name_ru' => 'Учтепа тумани', 'code' => 'TS008'],
            ['region_code' => 'TS', 'name_uz' => 'Yakkasaroy tumani', 'name_ru' => 'Яккасарой тумани', 'code' => 'TS009'],
            ['region_code' => 'TS', 'name_uz' => 'Yangihayon tumani', 'name_ru' => 'Янгиҳаён тумани', 'code' => 'TS010'],
            ['region_code' => 'TS', 'name_uz' => 'Yunusobod tumani', 'name_ru' => 'Юнусобод тумани', 'code' => 'TS011'],
            ['region_code' => 'TS', 'name_uz' => 'Yashnobod tumani', 'name_ru' => 'Яшнобод тумани', 'code' => 'TS012'],
        ];

        foreach ($districts as $districtData) {
            $regionId = $regions[$districtData['region_code']] ?? null;
            
            if ($regionId) {
                District::create([
                    'region_id' => $regionId,
                    'name_uz' => $districtData['name_uz'],
                    'name_ru' => $districtData['name_ru'],
                    'code' => $districtData['code'],
                    'is_active' => true,
                ]);
            }
        }
    }
}