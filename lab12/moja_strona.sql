-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 26, 2024 at 04:16 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `moja_strona`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kategorie`
--

CREATE TABLE `kategorie` (
  `id` int(11) NOT NULL,
  `matka` int(11) NOT NULL DEFAULT 0,
  `nazwa` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategorie`
--

INSERT INTO `kategorie` (`id`, `matka`, `nazwa`) VALUES
(1, 0, 'Elektronika'),
(2, 1, 'Komputery'),
(3, 1, 'Monitory'),
(4, 2, 'Laptopy'),
(5, 4, 'Chromebooki'),
(6, 3, '24 cale'),
(7, 3, '27 cali'),
(8, 0, 'Akcesoria komputerowe'),
(9, 1, 'Smartfony');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `klienci`
--

CREATE TABLE `klienci` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `haslo` varchar(255) NOT NULL,
  `imie` varchar(100) DEFAULT NULL,
  `nazwisko` varchar(100) DEFAULT NULL,
  `data_rejestracji` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `klienci`
--

INSERT INTO `klienci` (`id`, `email`, `haslo`, `imie`, `nazwisko`, `data_rejestracji`) VALUES
(1, 'kamilex987@gmail.com', '$2y$10$7VGgcv2STkaKpscAC8lsi.Fcn205AhWpWgy7s293lzpIeRITAfg9K', 'Kamil', 'L', '2024-12-24 17:18:41'),
(2, 'test@gmail.com', '$2y$10$QxqIxdA0.sBdmMHQCbMraOqeXyvK9NjTdIrVhfn4vkGVk7fLHc6fC', 'Test', 'Testowy', '2024-12-24 17:19:55');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `koszyki`
--

CREATE TABLE `koszyki` (
  `id` int(11) NOT NULL,
  `klient_id` int(11) NOT NULL,
  `produkt_id` int(11) NOT NULL,
  `ilosc` int(11) DEFAULT 1,
  `data_dodania` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `koszyki`
--

INSERT INTO `koszyki` (`id`, `klient_id`, `produkt_id`, `ilosc`, `data_dodania`) VALUES
(1, 1, 6, 2, '2024-12-24 17:18:55'),
(2, 1, 5, 1, '2024-12-24 17:18:57'),
(3, 1, 1, 1, '2024-12-24 17:18:58'),
(4, 1, 2, 1, '2024-12-24 17:19:00'),
(5, 1, 4, 2, '2024-12-24 17:19:07'),
(6, 2, 3, 1, '2024-12-24 17:20:12'),
(7, 2, 7, 1, '2024-12-24 17:20:18'),
(8, 1, 9, 1, '2024-12-25 16:36:03'),
(9, 1, 10, 3, '2024-12-25 18:39:22'),
(11, 1, 13, 1, '2024-12-26 16:15:30');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `page_list`
--

CREATE TABLE `page_list` (
  `id` int(11) NOT NULL,
  `page_title` varchar(255) NOT NULL,
  `page_content` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `alias` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `page_list`
--

INSERT INTO `page_list` (`id`, `page_title`, `page_content`, `status`, `alias`) VALUES
(1, 'glowna', '<main>\r\n    <article>\r\n        <header class=\"artheader\">\r\n            <h2> Kilka słów o komputerach </h2>\r\n        </header>\r\n        <section>\r\n            <header>\r\n                <h3> Co to jest komputer? </h3>\r\n            </header>\r\n            <img style=\"float:left; margin:6px\" src=\"img/1.jpg\" width=\"200\" height=\"160\" alt=\"komputer1\">\r\n                <span>\r\n                 Komputer (od ang. computer); dawniej: mózg elektronowy, elektroniczna maszyna cyfrowa, maszyna matematyczna – maszyna przeznaczona do przetwarzania informacji, które da się zapisać w formie ciągu cyfr albo sygnału ciągłego. Maszyna roku tygodnika „Time” w 1982 roku.\r\n                </span>\r\n                <br><br>\r\n                Mimo że mechaniczne maszyny liczące istniały od wielu stuleci, komputery w sensie współczesnym pojawiły się dopiero w połowie XX wieku, gdy zbudowano pierwsze komputery elektroniczne. Miały one rozmiary sporych pomieszczeń i zużywały kilkaset razy więcej energii niż współczesne komputery osobiste (PC), a jednocześnie miały miliardy razy mniejszą moc obliczeniową. Współcześnie są prowadzone także badania nad komputerami biologicznymi i optycznymi.\r\n                <br><br>\r\n                Małe komputery mogą zmieścić się nawet w zegarku i są zasilane baterią. Komputery osobiste stały się symbolem ery informatycznej. Najliczniejszymi maszynami liczącymi są systemy wbudowane sterujące najróżniejszymi urządzeniami – od odtwarzaczy MP3 i zabawek po roboty przemysłowe.\r\n                <br><br>	\r\n        </section>\r\n        \r\n        <section>\r\n            <header>\r\n                <h3> Komputery i inne maszyny liczące </h3>\r\n            </header>\r\n            \r\n                <img style=\"float:left; margin:6px\" src=\"img/3.jpg\" width=\"270\" height=\"250\" alt=\"komputer2\">\r\n                <img style=\"float:right; margin:6px \"  src=\"img/2.jpg\" width=\"270\" height=\"250\" alt=\"komputer3\">						\r\n                <u>Komputer od typowego kalkulatora odróżnia zdolność wykonywania wielokrotnie, automatycznie powtarzanych obliczeń, według algorytmicznego wzorca zwanego programem</u>, gdy tymczasem kalkulator może zwykle wykonywać tylko pojedyncze działania. Granica jest tu umowna, ponieważ taką definicję komputera spełniają też kalkulatory programowalne (naukowe, inżynierskie), jednak kalkulatory służą tylko do obliczeń numerycznych, podczas gdy nazwa komputer najczęściej dotyczy urządzeń wielofunkcyjnych.\r\n                <br><br>\r\n                Jakkolwiek istnieją mechaniczne urządzenia liczące, które potrafią realizować całkiem złożone programy, zazwyczaj nie zalicza się ich do komputerów. Warto jednak pamiętać, że prawzorem komputera jest abstrakcyjny model zwany maszyną Turinga, a pierwsze urządzenia ułatwiające obliczenia były znane w starożytności, na przykład abakus z 440 p.n.e.\r\n                <br><br>\r\n                W początkowym okresie rozwoju komputerów budowano komputery zerowej generacji na przekaźnikach i elementach mechanicznych.\r\n                <br><br>\r\n                Właściwie wszystkie współczesne komputery to maszyny elektroniczne. Próby budowania komputerów optycznych (wykorzystujących przełączniki optyczne), optoelektronicznych (połączenie elementów optycznych i elektronicznych), biologicznych (wykorzystujące wypreparowane komórki nerwowe) czy molekularnych (wykorzystujące jako bramki logiczne pojedyncze cząsteczki) są jeszcze w powijakach i do ich praktycznego zastosowania jest wciąż długa droga. Innym rodzajem komputera jest komputer kwantowy, którego układ przetwarzający dane wykorzystuje efekty fizyczne wynikające z mechaniki kwantowej.\r\n                <br><br>\r\n        </section>\r\n        \r\n        <section>\r\n\r\n            <header>\r\n                <h3>Podstawowe elementy komputera </h3>\r\n            </header>\r\n            <div class=\"last\">\r\n                Większość współczesnych komputerów opartych jest na tak zwanej architekturze von Neumanna (od nazwiska Johna von Neumanna) i składa się z trzech typów podstawowych elementów:\r\n                <br><br>\r\n                -procesora – podzielonego na część arytmetyczno-logiczną, czyli układu, który faktycznie wykonuje wszystkie konieczne obliczenia oraz część sterującą (często obok CPU obecny jest także GPU czy procesor dźwięku)\r\n                <br><br>\r\n                -pamięci RAM – (od ang. Random Access Memory), czyli układy scalone, które przechowują program i dane (umożliwia to między innymi samomodyfikację programu) oraz bieżące wyniki obliczeń procesora i stale, na bieżąco wymienia dane z procesorem\r\n                <br><br>\r\n                -urządzeń wejścia/wyjścia – które służą do komunikacji komputera z otoczeniem.\r\n            </div>\r\n        </section>\r\n\r\n    </article>\r\n\r\n\r\n</main>', 1, 'glowna'),
(2, 'historia_komputerow', '\r\n		<main>\r\n			<article>\r\n				<header class=\"artheader\">\r\n					<h2> Historia komputerów w pigułce </h2>\r\n				</header>\r\n				<section>\r\n					<header>\r\n						<h3> Pierwsze kroki</h3>\r\n					</header>\r\n					<img style=\"float:left; margin:6px\" src=\"img/6.jpg\" width=\"150\" height=\"80\" alt=\"abakus\">\r\n					Historia maszyn analitycznych zaczęła się znacznie wcześniej niż budowa komputera. Już w czasach starożytnych, ludzie próbowali przyspieszyć swoje obliczenia, co pchało ich do tworzenia najróżniejszych narzędzi. Na początku były to proste liczydła, które z czasem zamieniono na coraz bardziej zaawansowane urządzenia mogące wykonywać wiele różnorodnych operacji jednocześnie.\r\n					\r\n					<br><br>\r\n					Jedną z najważniejszych osób związanych z szeroko pojętą informatyką jest urodzony w 1646 r. niemiecki polihistor Gottfried Wilhelm Leibniz – zapisał się on na kartach historii matematyki jako twórca rachunku różniczkowego, całkowego, a także arytmetyki binarnej, będącej podstawą obliczeń dzisiejszych komputerów. Uczony uzdolniony był również mechanicznie, co pozwoliło mu stworzyć pierwszą maszynę liczącą, która wykorzystywała zapis zero jedynkowy.\r\n					<br><br>\r\n					Na podstawie prac Leibniza w 1833 Charles Babbage, opracował urządzenie, którego zasady działania były zbliżone do współczesnych konstrukcji. Ze względu na brak możliwości finansowych i niski poziom wiedzy technicznej nigdy nie zostało ono wykonane. Wielokrotnie sprawdzano jego założenia teoretyczne, które okazały się całkowicie prawidłowe.\r\n					<br><br>\r\n					Kolejne przełomowe prace, które wpłynęły na historię i rozwój informatyki, pojawiły się w XX w. Dzieła Alana Turinga, który również pracował nad stworzeniem Colosussa, sprawiły, że zupełnie inaczej zaczęto postrzegać możliwości mechanizmów analitycznych, a hipotetyczna maszyna Turinga, nadała kształt, jaki przybierze późniejsza budowa komputera. Podobny wkład w rozwój tej dziedziny miał John von Neumann, który stworzył projekt pierwszej architektury sprzętowej.\r\n				</section>\r\n				<section>\r\n					<header>\r\n						<h3> <br>Długa droga do współczesności </h3>\r\n					</header>\r\n					<img style=\"float:left; margin:6px\" src=\"img/4.webp\" width=\"500\" height=\"300\" alt=\"robotron\">\r\n					Od ogromnych maszyn lat 40. do współczesnych laptopów sprzęt komputerowy musiał przejść bardzo długą drogę. Dopiero pod koniec lat 70. stworzono pierwsze projekty, które mogły być wykorzystywane powszechnie. Pierwszym komputerem ogólnie dostępnym był Apple I, stworzony przez Steve’a Jobsa i Steve’a Woźniaka. Posiadał on procesor o szybkości 1 Mhz, 4 KB ram z możliwością rozszerzenia do 48 KB i pamięć graficzną 1 KB (umożliwiała ona wyświetlanie na ekranie monitora tekstu w 40 kolumnach i 24 wierszach). Urządzenie zostało dobrze przyjęte, co pozwoliło założycielom firmy Apple na rozpoczęcie prac nad kolejnymi modelami.\r\n					<br><br>\r\n					W latach osiemdziesiątych swoje sukcesy święciły takie komputery jak IBM PC, który był konstrukcją naprawdę udaną. Był on kompatybilny z większością sprzętu i akcesoriów dostępnych na rynku, przez co konkurował z równie popularnym w tamtych czasach Macintoshem 128 k produkowanym od 1984 r. Lata osiemdziesiąte XX w. to również wysyp innych urządzeń, które były mniej lub bardziej popularne.\r\n					<br><br>\r\n					Jednym z najlepiej sprzedających się urządzeń na całym świecie był komputer Commodore 64, który był reklamowany jako najlepsza platforma do gier, zresztą nie było to kłamstwem, gdyż na tym sprzęcie można było zagrać w setki tytułów. Oprócz gier, maszyna była w pełni funkcjonalna, pozwalała na pracę w arkuszu kalkulacyjnym, edycję tekstu, a także miała zainstalowane proste kompilatory ówczesnych języków programowania.\r\n				</section>	\r\n				<section>\r\n					<header>\r\n						<br><br>\r\n						<h3> <br><br>Początki komputerów w Polsce </h3>\r\n					</header>\r\n					Pierwszy komputer w Polsce zbudowano w 1950 r., był to <u>przekaźnikowy GAM-1 </u>skonstruowany przez Zdzisława Pawlaka. Służył do celów dydaktycznych. Kolejne maszyny produkowane w Polsce były dziełem Wrocławskich Zakładów Elektromechanicznych, które produkowały jednostki Odra w latach 70. i jednostki Elwro w latach 80. W tym czasie w Polsce produkowano również maszyny Meritum. W 1987 r. pojawiły się jednostki Mazovia 1016 będące kopią IBM PC. Zostały one jednak wyparte przez tzw. składaki, czyli komputery różnych firm budowanych z ogólnie dostępnych części kompatybilnych z IBM PC – jedną z najpopularniejszych firm w tamtych czasach był Optimus, który w 2011 roku zmienił nazwę na CD Projekt Red (od swojej spółki córki)\r\n				</section>\r\n				<section>\r\n					<header>\r\n						<h3> <br>Powstanie laptopów </h3>\r\n						<div id=\"animacjaTestowa2\" class=\"test-block2\"><img style=\"float:left; margin:6px\" src=\"img/5.jpg\" width=\"200\" height=\"100\" alt=\"ThinkPad\"></div>\r\n						\r\n					</header>\r\n					Lata 90. to również rozwój komputerów przenośnych. Mimo że pierwszy laptop powstał w 1979, to zostały one spopularyzowane właśnie w latach 90. dzięki maszynom takim jak np. IBM ThinkPad. Przez dążenie do miniaturyzacji, jednostki tego typu stawały się coraz mniejsze, były one przeznaczone dla osób, które wykonują swoją pracę w podróży, a dzięki komputerowi mogli być na bieżąco ze swoimi zadaniami. Obecne laptopy nie ustępują możliwościami swoim stacjonarnym odpowiednikom, posiadają podobne podzespoły, a także doskonale radzą sobie z różnorodnymi zadaniami. Również cały czas powstają nowe rodzaje komputerów przenośnych; na przykład ultrabooki, urządzenia 2 w 1 łączące funkcje tabletu z laptopem.\r\n					<br><br> \r\n				</section>\r\n				<section class=\"last\">\r\n					<header>\r\n						<h2> <br>Jak zmieniła się technologia komputerów? </h2>\r\n					</header>\r\n					Współczesna technologia zmienia nasz świat w niesamowitym tempie, najlepiej można to zaobserwować po rozwoju zaawansowanych maszyn. Urządzenia, które dziś nosimy w kieszeni, mają setki razy większą moc obliczeniową niż najlepsze jednostki lat 50, a przypomnieć należy, że zajmowały one ogromne ilości miejsca. <b>Nie wiadomo dokąd zaprowadzi nas dalszy rozwój techniki, ale z pewnością wpłynie on na nasze życie, tak jak pierwsze komputery wpłynęły na współczesność. </b>\r\n				</section>\r\n		\r\n		\r\n			</article>\r\n		</main>\r\n		\r\n		', 1, 'historia_k'),
(3, 'systemy_operacyjne', '\r\n		<main>\r\n			<article>\r\n				<header class=\"artheader\">\r\n					<h2> Systemy operacyjne </h2>\r\n				</header>\r\n\r\n				<section>\r\n					<header>\r\n					<h3> Co to jest system operacyjny? </h3>\r\n					</header>\r\n				</section>\r\n				<span>\r\n				System operacyjny (ang. operating system, skrót OS) – oprogramowanie zarządzające systemem komputerowym, tworzące środowisko do uruchamiania i kontroli zadań, najczęściej w formie programów.\r\n				</span>\r\n				<br><br>\r\n				W celu uruchamiania i kontroli zadań użytkownika system operacyjny zajmuje się:\r\n				- planowaniem oraz przydziałem czasu procesora poszczególnym zadaniom,\r\n				- kontrolą i przydziałem pamięci operacyjnej dla uruchomionych zadań,\r\n				- dostarczaniem mechanizmów do synchronizacji zadań i komunikacji pomiędzy zadaniami,\r\n				- obsługą sprzętu oraz zapewnieniem równolegle wykonywanym zadaniom jednolitego, wolnego od interferencji dostępu do sprzętu.\r\n				<br>\r\n				Dodatkowe przykładowe zadania, którymi może, ale nie musi, zajmować się system operacyjny to:\r\n				- ustalanie połączeń sieciowych,\r\n				- zarządzanie plikami.\r\n				<br><br>\r\n				Większość współczesnych systemów operacyjnych posiada środowiska graficzne ułatwiające komunikację maszyny z użytkownikiem.\r\n\r\n				<section>\r\n					<header>\r\n						<h3> Przykłady systemów operacyjnych </h3>\r\n\r\n<a href=\"https://www.microsoft.com/pl-pl/windows\" target=\"_blank\"> <h3> Windows </h3> </a>\r\n						</header>\r\n					<img style=\"float:right; margin:6px\" src=\"img/7.png\" width=\"350\" height=\"200\" alt=\"windows11\">\r\n					<img style=\"float:right; margin:6px\" src=\"img/8.png\" width=\"350\" height=\"200\" alt=\"windows1.01\">\r\n				\r\n					Microsoft Windows (ang. windows „okna”, IPA: [maɪkɹoʊsɑːft ˈwɪndoʊz]) – rodzina systemów operacyjnych stworzonych przez firmę Microsoft. Systemy rodziny Windows działają na telefonach, smartfonach, serwerach, systemach wbudowanych oraz na komputerach osobistych, z którymi są najczęściej kojarzone.\r\n					<br><br>\r\n					Prezentację pierwszego graficznego środowiska pracy z rodziny Windows firmy Microsoft przeprowadzono w listopadzie 1984. Wówczas była to jedynie graficzna nakładka na system operacyjny MS-DOS, powstała w odpowiedzi na rosnącą popularność graficznych interfejsów użytkownika, takich jakie prezentowały na przykład komputery Macintosh. Nakładka, a później system operacyjny Windows po pewnym czasie zdominowała światowy rynek komputerów osobistych. Pierwszym stabilnym wydaniem był Windows 1.01. Windows 1.00 był wersją beta, nigdy nie wydaną.\r\n				</section>\r\n				\r\n				<section>\r\n					<header>\r\n						<a href=\"https://kernel.org/\" target=\"_blank\"> <h3> Linux </h3> </a>\r\n					</header>\r\n					<img style=\"float:right; margin:6px\" src=\"img/10.png\" width=\"150\" height=\"300\" alt=\"android\">\r\n					<img style=\"float:right; margin:6px\" src=\"img/9.webp\" width=\"350\" height=\"200\" alt=\"ubuntu\">\r\n					Linux – rodzina uniksopodobnych systemów operacyjnych opartych na jądrze Linux. Linux jest jednym z przykładów wolnego i otwartego oprogramowania (FLOSS): jego kod źródłowy może być dowolnie wykorzystywany, modyfikowany i rozpowszechniany. Od kwietnia 2017 roku Android (z zaadaptowanym jądrem Linuxa) oficjalnie jest najpopularniejszym systemem operacyjnym na świecie.\r\n					<br><br>\r\n					Pierwsza wersja jądra Linux została udostępniona publicznie 17 września 1991 dla architektury komputera PC, wykorzystującego mikroprocesor o architekturze IA-32. Do jądra dołączono narzędzia systemowe zwane też „Development Kit” oraz biblioteki z projektu GNU, aby otrzymać nadający się do użytku system operacyjny. Z tego powodu powstała też alternatywna nazwa kompletnego systemu: GNU/Linux. Jest udostępniany w formie dystrybucji Linuksa, składających się z jądra (niekiedy zmodyfikowanego w stosunku do oficjalnej wersji) i zestawu pakietów oprogramowania dobranego do różnorodnych wymagań. Dystrybucje zawierają głównie oprogramowanie na licencjach FLOSS, jednak najczęściej zawierają też lub pozwalają na doinstalowanie pewnej liczby programów na licencjach własnościowych.\r\n					<br><br>\r\n					Jednym z zastosowań Linuksa są środowiska serwerowe, dla których komercyjne wsparcie oferują firmy komputerowe, jak IBM, Oracle, Dell, Microsoft, Hewlett-Packard, Red Hat i Novell. Linux instalowany jest na różnorodnym sprzęcie komputerowym, np. komputerach biurkowych, superkomputerach i systemach wbudowanych, jak telefony komórkowe, routery oraz telewizory (np. LG, Samsung).\r\n				</section>\r\n				\r\n				<section class=\"last\">\r\n					<header>\r\n						<a href=\"https://www.apple.com/pl/macos\" target=\"_blank\"> <h3> macOS </h3> </a>\r\n					</header>\r\n					<img style=\"float:right; margin:6px\" src=\"img/11.png\" width=\"350\" height=\"200\" alt=\"macos1\">\r\n\r\n					macOS (wymowa: mækoʊɛs, dawniej OS X, a także Mac OS X) – rodzina uniksowych systemów operacyjnych produkowanych i rozprowadzanych przez Apple Inc. Dostępny oficjalnie jedynie dla komputerów Macintosh, instalowany fabrycznie na nich od 2002 roku. 13 czerwca 2016 roku na WWDC została ogłoszona zmiana nazwy na macOS w związku z potrzebą unifikacji nazw używanych przez Apple dla swoich systemów operacyjnych (iOS, watchOS, tvOS).\r\n					<br><br>\r\n					macOS jest zbudowany na podstawie dawnego systemu NeXTStep z drugiej połowy lat 80., wykupionym przez Apple wraz z producentem tegoż systemu NeXT w 1996 r. Jest on następcą systemu Mac OS 9, wydanego w 1999 r. i ostatniej wersji „klasycznego” Mac OS, który był głównym systemem komputerów Macintosh od 1984 roku.\r\n					<br><br>\r\n					macOS oryginalnie działał jedynie na Macach o architekturze PowerPC. W 2006 wydano pierwszą wersję dla nowych Maców o architekturze x86 – Mac OS X 10.4. W 2005 roku wydano pierwszą wersję w pełni zgodną z Single UNIX Specification w wersji trzeciej – Mac OS X 10.5, działający zarówno na Macach PowerPC i x86 z pomocą technologii zwanej Universal binary, czyli formatu plików wykonywalnych działających na obu architekturach. 10.6 Snow Leopard to ostatnia wersja, która obsługuje programy dla procesorów PowerPC przy użyciu narzędzia Rosetta. Mac OS X 10.7 przestaje również obsługiwać komputery o architekturze 32-bitowej, ograniczając się do procesorów 64-bitowych.\r\n					<br><br>\r\n					<img style=\"float:right; margin:6px\" src=\"img/12.png\" width=\"250\" height=\"160\" alt=\"macos2\">\r\n					\r\n					Pierwszą opublikowaną wersją systemu był Mac OS X Server 1.0 z 1999 roku i Cheetah (wersja desktopowa) wydany 24 marca 2001 roku. Poszczególne wydania, do wydania 10.8 były nazywane nazwami pochodzącymi od wielkich kotów, np. OS X 10.8 nosi nazwę handlową Mountain Lion, czyli puma płowa. Obecnie nazwy systemów macOS pochodzą od parków krajobrazowych w Kalifornii, np. macOS Sierra. Wersja serwerowa, OS X Server technicznie nie różniła się wiele od OS X – posiadała jedynie wiele wbudowanych aplikacji serwerowych. Obecnie, czyli od wersji Lion nie ma już możliwości zakupu OS X Server osobno – jest on dostępny jako pakiet aplikacji do macOS, a także ładowany do wersji serwerowych Maców Mini oraz Pro.\r\n					<br><br>\r\n					iOS, system firmy Apple działający na urządzeniach przenośnych iPhone, iPod Touch, oraz Apple TV drugiej i trzeciej generacji został stworzony w oparciu o macOS, z którym to dzieli jądro i wiele mechanizmów działania, jednak aplikacje dla macOS są tworzone za pomocą Cocoa, a dla iOS za pomocą Cocoa Touch dostosowanego do ekranów dotykowych.\r\n				</section>\r\n			</article>\r\n		</main>\r\n		\r\n', 1, 'systemy'),
(4, 'jezyki_programowania', '\r\n		<main>\r\n			<article>\r\n				<header class=\"artheader\">\r\n					<h2> Języki programowania </h2>\r\n				</header>\r\n				<section>\r\n					<header>\r\n					<h3> Co to jest język programowania </h3>\r\n					</header>\r\n					<span>\r\n					Język programowania – zbiór zasad określających, kiedy ciąg symboli tworzy program komputerowy oraz jakie obliczenia opisuje. Teorią języków programowania w informatyce zajmuje się teoria języków formalnych.\r\n					</span>\r\n					<br><br>\r\n					Podobnie jak języki naturalne, język programowania składa się ze zbiorów reguł syntaktycznych oraz semantyki, które opisują, jak należy budować poprawne wyrażenia oraz jak komputer ma je rozumieć. Wiele języków programowania posiada pisemną specyfikację swojej składni oraz semantyki, lecz inne zdefiniowane są jedynie przez oficjalne implementacje.\r\n					<br>\r\n					Język programowania pozwala na precyzyjny zapis algorytmów oraz innych zadań, jakie komputer ma wykonać. W niektórych pracach pojęcie języka programowania jest ograniczane wyłącznie do tych języków, w których można zapisać wszystkie istniejące algorytmy – od strony matematycznej oznacza to, że język musi być przynajmniej kompletny w sensie Turinga, jednak można się także spotkać z wykorzystaniem tego pojęcia na określenie również bardziej ograniczonych języków.\r\n				</section>\r\n\r\n				<section class=\"last\">\r\n					<header>\r\n						<h3> Przykładowe języki programowania </h3>\r\n					</header>\r\n					<a href=\"https://www.iso.org/standard/74528.html\" target=\"_blank\"> <h3> C </h3> </a>\r\n					<img style=\"float:right; margin:6px\" src=\"img/13.webp\" width=\"250\" height=\"150\" alt=\"c\">\r\n					C – imperatywny, proceduralny język programowania ogólnego przeznaczenia, stworzony na początku lat 70. XX wieku przez Dennisa Ritchiego, ówczesnego pracownika Bell Labs. W języku C powstały narzędzia systemowe dla Uniksa, a potem również kod systemu Unix. Język C został ustandaryzowany w 1989 roku przez ANSI. Od tego czasu jego rozwojem zajmuje się grupa robocza w ramach ISO.\r\n					<br><br>\r\n					Język C powstawał jako rozwinięcie języka B, wzbogacając się stopniowo o kolejne funkcje. Okresy najszybszego rozwoju języka C to lata 1972–1973 oraz 1977–1979. Z kolei lata 1980. to czas zdobywania przez niego popularności, czego efektem była dostępność kompilatorów dla praktycznie wszystkich używanych wtedy architektur komputerów i systemów operacyjnych.\r\n					<br><br>\r\n					C jest jednym z popularniejszych języków programowania. Służy zarówno do pisania elementów systemów operacyjnych, jak i aplikacji użytkowych. Mimo to spotyka się z krytyką z powodu pewnych decyzji projektowych, takich jak priorytety operatorów lub nadmiernie lakoniczna składnia.\r\n					<a href=\"https://isocpp.org/\" target=\"_blank\"> <h3> C++ </h3> </a>\r\n					<img style=\"float:right; margin:6px\" src=\"img/14.png\" width=\"250\" height=\"150\" alt=\"c++\">\r\n					C++ – język programowania ogólnego przeznaczenia. Język został zaprojektowany przez Bjarne Stroustrupa jako rozszerzenie języka C o obiektowe mechanizmy abstrakcji danych i silną statyczną kontrolę typów. Zachowanie zgodności z językiem C na poziomie kodu źródłowego pozostaje jednym z podstawowych celów projektowych kolejnych standardów języka.\r\n					<br><br>\r\n					Umożliwia abstrakcję danych oraz stosowanie kilku paradygmatów programowania: proceduralnego, obiektowego i generycznego, a także funkcyjnego i modularnego. Charakteryzuje się wysoką wydajnością kodu wynikowego, bezpośrednim dostępem do zasobów sprzętowych i funkcji systemowych, łatwością tworzenia i korzystania z bibliotek (napisanych w C++, C lub innych językach), niezależnością od konkretnej platformy sprzętowej lub systemowej (co gwarantuje wysoką przenośność kodów źródłowych) oraz niewielkim środowiskiem uruchomieniowym. Podstawowym obszarem jego zastosowań są aplikacje i systemy operacyjne.\r\n					<br><br>\r\n					W latach 90. XX wieku język C++ zdobył pozycję jednego z najpopularniejszych języków programowania ogólnego przeznaczenia. Według ankiety z 2015 roku, liczba programistów C++ wynosi około 4,4 miliona.\r\n					<a href=\"http://www.java.com/\" target=\"_blank\"> <h3> Java </h3> </a>\r\n					<img style=\"float:right; margin:6px\" src=\"img/15.png\" width=\"350\" height=\"150\" alt=\"java\">\r\n					Java (wym. dżawa) – współbieżny, oparty na klasach, obiektowy język programowania ogólnego zastosowania. Został stworzony przez grupę roboczą pod kierunkiem Jamesa Goslinga z firmy Sun Microsystems. Java jest językiem tworzenia programów źródłowych kompilowanych do kodu bajtowego, czyli postaci wykonywanej przez maszynę wirtualną. Język cechuje się silnym typowaniem. Jego podstawowe koncepcje zostały przejęte z języka Smalltalk (maszyna wirtualna, zarządzanie pamięcią) oraz z języka C++ (duża część składni i słów kluczowych).\r\n					<br><br>\r\n					Społeczność programistów określana jest jako Java User Group.\r\n					<br><br>\r\n					Oracle regularnie wydaje nowe wersje Javy co pół roku. We wrześniu 2021 Oracle ogłosiło skrócenie okresu wydawania wersji LTS z trzyletniego na dwuletni.\r\n					<a href=\"https://www.python.org/\" target=\"_blank\"> <h3> Python </h3> </a>\r\n					<img style=\"float:right; margin:6px\" src=\"img/16.webp\" width=\"250\" height=\"150\" alt=\"python\">\r\n					Python – język programowania wysokiego poziomu ogólnego przeznaczenia, o rozbudowanym pakiecie bibliotek standardowych, którego ideą przewodnią jest czytelność i klarowność kodu źródłowego. Jego składnia cechuje się przejrzystością i zwięzłością.\r\n					<br><br>\r\n					Python wspiera różne paradygmaty programowania: obiektowy, imperatywny oraz w mniejszym stopniu funkcyjny. Posiada w pełni dynamiczny system typów i automatyczne zarządzanie pamięcią, będąc w tym podobnym do języków Perl, Ruby, Scheme czy Tcl. Podobnie jak inne języki dynamiczne jest często używany jako język skryptowy. Interpretery Pythona są dostępne na wiele systemów operacyjnych.\r\n					<br><br>\r\n					Python rozwijany jest jako projekt Open Source zarządzany przez Python Software Foundation, która jest organizacją non-profit. Standardową implementacją języka jest CPython (napisany w C), ale istnieją też inne, np. Jython (napisany w Javie), CLPython napisany w Common Lisp, IronPython (na platformę .NET) i PyPy (napisany w Pythonie, zob. bootstrap).\r\n				</section>\r\n\r\n			</article>\r\n		</main>\r\n		', 1, 'jezyki'),
(5, 'powstanie_internetu', '\r\n		<main>\r\n			<article>\r\n				<header class=\"artheader\">\r\n					<h2> Powstanie i rozwój internetu w latach 1957-2000 </h2>\r\n				</header>\r\n				<div class=\"table\">\r\n					<table width=\"1000\" height=\"30\" align=\"center\" >\r\n						<tr>\r\n							<td width=\"200\" valign=\"center\">\r\n							<b> Data </b>\r\n							</td>\r\n							<td width=\"200\" valign=\"center\">\r\n							<b> Wydarzenie </b>\r\n							</td>\r\n						</tr>\r\n						<tr>\r\n							<td width=\"200\" valign=\"center\">\r\n							1957\r\n							</td>\r\n							<td width=\"200\" valign=\"center\">				\r\n							W Departamencie Obrony USA powstaje ARPA (Advanced Research Projects Agency)\r\n							</td>\r\n						</tr>\r\n						<tr>\r\n							<td width=\"200\" valign=\"center\">\r\n							1960-1968	\r\n							</td>\r\n							<td width=\"200\" valign=\"center\">				\r\n							Powstają założenia budowy sieci pakietowych (L. Kleinrock, I. Licklider, W. Clark, P. Baran)\r\n							</td>\r\n						</tr>\r\n						<tr>\r\n							<td width=\"200\" valign=\"center\">\r\n							1969\r\n							</td>\r\n							<td width=\"200\" valign=\"center\">				\r\n							Pierwsze połączenie czterech węzłów sieci Arpanet\r\n							</td>\r\n						</tr>			\r\n						<tr>\r\n							<td width=\"200\" valign=\"center\">\r\n							1971\r\n							</td>\r\n							<td width=\"200\" valign=\"center\">				\r\n							Pierwszy program do przesyłania wiadomości elektronicznych (R. Tomilson)\r\n							</td>\r\n						</tr>\r\n						<tr>\r\n							<td width=\"200\" valign=\"center\">\r\n							1973\r\n							</td>\r\n							<td width=\"200\" valign=\"center\">				\r\n							Pierwsze połączenie sieciowe pomiędzy Ameryką a Europą, powstanie koncepcji Internetu (B. Kahn, V. Cerf)\r\n							</td>\r\n						</tr>\r\n						<tr>\r\n							<td width=\"200\" valign=\"center\">\r\n							1974\r\n							</td>\r\n							<td width=\"200\" valign=\"center\">				\r\n							Powstanie protokołu TCP (Transmission Control Protocol)\r\n							</td>\r\n						</tr>\r\n						<tr>\r\n							<td width=\"200\" valign=\"center\">\r\n							1977\r\n							</td>\r\n							<td width=\"200\" valign=\"center\">							\r\n							Powstanie pierwszej listy dyskusyjnej i opracowanie protokołu TCP/IP\r\n							</td>\r\n						</tr>\r\n						<tr>\r\n							<td width=\"200\" valign=\"center\">\r\n							1979\r\n							</td>\r\n							<td width=\"200\" valign=\"center\">				\r\n							Powstanie tekstowej grupy dyskusyjnej - USENET (T. Truscott, J. Elis, S. Bellovin)\r\n							</td>\r\n						</tr>\r\n						<tr>\r\n							<td width=\"200\" valign=\"center\">\r\n							1981\r\n							</td>\r\n							<td width=\"200\" valign=\"center\">				\r\n							Opracowanie koncepcji hipertekstowej bazy danych (T. Nelson)\r\n							</td>\r\n						</tr>\r\n						<tr>\r\n							<td width=\"200\" valign=\"center\">\r\n							1984\r\n							</td>\r\n							<td width=\"200\" valign=\"center\">				\r\n							Pojawienie się pierwszych \"uśmieszków\" (smileys) - tekstowych znaków wyrażających emocje, używanych w poczcie elektronicznej i listach dyskusyjnych\r\n							</td>\r\n						</tr>\r\n						<tr>\r\n							<td width=\"200\" valign=\"center\">\r\n							1984\r\n							</td>\r\n							<td width=\"200\" valign=\"center\">				\r\n							Powstanie systemu tłumaczenia nazw komputerów na cyfry, czyli DNS - Domain Name System (P. Mockaperis)\r\n							</td>\r\n						</tr>\r\n						<tr>\r\n							<td width=\"200\" valign=\"center\">\r\n							1985\r\n							</td>\r\n							<td width=\"200\" valign=\"center\">				\r\n							Rejestracja pierwszej domeny: symbolics.com\r\n							</td>\r\n						</tr>\r\n						<tr>\r\n							<td width=\"200\" valign=\"center\">\r\n							1988\r\n							</td>\r\n							<td width=\"200\" valign=\"center\">				\r\n							Pojawienie się na dużą skalę pierwszego wirusa w sieci, założenie organizacji IANA (Internet Assigned Numbers Authority) zajmującej się rejestracją domen (J. Postel), powstanie systemu internetowych pogawędek IRC - Internet Relay Chat (J. Oikarinen)\r\n							</td>\r\n						</tr>\r\n						<tr>\r\n							<td width=\"200\" valign=\"center\">\r\n							1990\r\n							</td>\r\n							<td width=\"200\" valign=\"center\">				\r\n							Powstanie systemu WWW - World Wide Web (T. Berens-Lee)\r\n							</td>\r\n						</tr>\r\n						<tr>\r\n							<td width=\"200\" valign=\"center\">\r\n							1991\r\n							</td>\r\n							<td width=\"200\" valign=\"center\">				\r\n							Początek usługi WWW zainicjowanej w CERN, opracowanie systemu Gopher (P. Linder, M. McCahil)\r\n							</td>\r\n						</tr>\r\n						<tr>\r\n							<td width=\"200\" valign=\"center\">\r\n							1992\r\n							</td>\r\n							<td width=\"200\" valign=\"center\">				\r\n							Powstanie pierwszej przeglądarki Mosaic (M. Andreessen, E. Bina)\r\n							</td>\r\n						</tr>\r\n						<tr>\r\n							<td width=\"200\" valign=\"center\">\r\n							1994\r\n							</td>\r\n							<td width=\"200\" valign=\"center\">				\r\n							Utworzenie katalogu zasobów internetowych \"Yahoo\" (D. Filo, J. Yang), powstanie organizacji koordynującej rozwój - Internet Society\r\n							</td>\r\n						</tr>			\r\n						<tr>\r\n							<td width=\"200\" valign=\"center\">\r\n							1995\r\n							</td>\r\n							<td width=\"200\" valign=\"center\">				\r\n							Powstanie przeglądarki Netscape Navigator (M. Andreessen), prezentacja języka Java\r\n							</td>\r\n						</tr>			\r\n						<tr>\r\n							<td width=\"200\" valign=\"center\">\r\n							1996\r\n							</td>\r\n							<td width=\"200\" valign=\"center\">				\r\n							Wprowadzenie przez prezydenta B. Clintona programu rozwoju Internetu w gospodarce USA\r\n							</td>\r\n						</tr>\r\n						<tr>\r\n							<td width=\"200\" valign=\"center\">\r\n							1999\r\n							</td>\r\n							<td width=\"200\" valign=\"center\">				\r\n							Początek sieci szerokopasmowych tzw. Internetu 2\r\n							</td>\r\n						</tr>\r\n						<tr>\r\n							<td width=\"200\" valign=\"center\">\r\n							2000\r\n							</td>\r\n							<td width=\"200\" valign=\"center\">				\r\n							Początek ery dojrzałego Internetu, rok portali\r\n							</td>\r\n						</tr>\r\n	\r\n						</table>	\r\n\r\n				</div>\r\n				\r\n		\r\n\r\n			</article>\r\n		</main>\r\n		\r\n', 1, 'historia_i'),
(6, 'formularz_kontaktowy', '\r\n		\r\n		<main>\r\n			<article>\r\n				<header class=\"artheader\">\r\n					<h2>Formularz kontaktowy</h2>\r\n				</header>\r\n				\r\n				<div id=\"form\">\r\n					<form action=\"mailto:komputery.mojapasja@gmail.com\" method=\"post\">\r\n					<br>\r\n					<input type=\"text\" name=\"name\" id=\"name\" class=\"formField\" placeholder=\"Wpisz imię\"> \r\n					<br>\r\n					<input type=\"text\" name=\"surname\" id=\"surname\" class=\"formField\" placeholder=\"Wpisz nazwisko\"> \r\n					<br>\r\n					<input type=\"text\" name=\"email\" id=\"email\" class=\"formField\" placeholder=\"Wpisz adres email\"> \r\n					<br>\r\n					<textarea style= \"\" id=\"message\" name=\"message\" placeholder=\"Treść wiadomości\"></textarea>\r\n					<br>\r\n					<input type=\"submit\" value=\"Wyslij\">\r\n					</form>\r\n				</div>\r\n			</article>\r\n		</main>\r\n		\r\n\r\n\r\n\r\n', 1, 'kontakt'),
(7, 'javascript', '\r\n			<article>\r\n				<header class=\"artheader\">\r\n					<h2> JavaScript </h2>\r\n				</header>\r\n				JavaScript, w skrócie JS – skryptowy oraz wieloparadygmatowy język programowania, stworzony przez firmę Netscape, najczęściej stosowany na stronach internetowych. Twórcą JavaScriptu jest Brendan Eich. W połowie lat 90. XX wieku organizacja ECMA wydała na podstawie JavaScriptu standard języka skryptowego o nazwie ECMAScript, aktualnie rozwijaniem tego standardu zajmuje się komisja TC39.\r\n				<br><br>\r\n				\r\n				<h3>Wybór kolorów: </h3>\r\n				<FORM METHOD=\"POST\" NAME=\"background\">\r\n					<INPUT TYPE=\"button\" VALUE=\"żółty\" ONCLICK=\"changeBackground(\'#FFF000\')\">\r\n					<INPUT TYPE=\"button\" VALUE=\"czarny\" ONCLICK=\"changeBackground(\'#000000\')\">\r\n					<INPUT TYPE=\"button\" VALUE=\"biały\" ONCLICK=\"changeBackground(\'#FFFFFF\')\">\r\n					<INPUT TYPE=\"button\" VALUE=\"zielony\" ONCLICK=\"changeBackground(\'#00FF00\')\">\r\n					<INPUT TYPE=\"button\" VALUE=\"niebieski\" ONCLICK=\"changeBackground(\'#0000FF\')\">\r\n					<INPUT TYPE=\"button\" VALUE=\"pomarańczowy\" ONCLICK=\"changeBackground(\'#FF8000\')\">\r\n					<INPUT TYPE=\"button\" VALUE=\"szary\" ONCLICK=\"changeBackground(\'#c0c0c0\')\">\r\n					<INPUT TYPE=\"button\" VALUE=\"czerwony\" ONCLICK=\"changeBackground(\'#FF0000\')\">\r\n				</FORM>\r\n			\r\n				<div id=\"slider\"></div>\r\n				<span onclick=\"ustawslajd(1)\" style=\"cursor:pointer;\">[ 1 ]</span>\r\n				<span onclick=\"ustawslajd(2)\" style=\"cursor:pointer;\">[ 2 ]</span>\r\n				<span onclick=\"ustawslajd(3)\" style=\"cursor:pointer;\">[ 3 ]</span>\r\n			\r\n			\r\n				<h3>Konwerter: </h3>\r\n				<form name=\"converter\">\r\n					<p>\r\n						<label for=\"input\">Wprowadź wartość:</label>\r\n						<input type=\"text\" name=\"input\" id=\"input\" value=\"0\" oninput=\"convert(this.form, this.form.measure1, this.form.measure2)\">\r\n					</p>\r\n			\r\n					<p>\r\n						<label for=\"measure1\">Z:</label>\r\n						<select name=\"measure1\" id=\"measure1\" onchange=\"convert(this.form, this, this.form.measure2)\">\r\n							<option value=\"1\">metry</option>\r\n							<option value=\"0.01\">centymetry</option>\r\n							<option value=\"1000\">kilometry</option>\r\n						</select>\r\n			\r\n						<label for=\"measure2\">Na:</label>\r\n						<select name=\"measure2\" id=\"measure2\" onchange=\"convert(this.form, this.form.measure1, this)\">\r\n							<option value=\"1\">metry</option>\r\n							<option value=\"0.01\">centymetry</option>\r\n							<option value=\"1000\">kilometry</option>\r\n						</select>\r\n					</p>\r\n			\r\n					<p>\r\n						<label for=\"display\">Wynik:</label>\r\n						<input type=\"text\" name=\"display\" id=\"display\" readonly>\r\n					</p>\r\n			\r\n					<p>\r\n						<button type=\"button\" onclick=\"this.form.reset()\">Wyczyść</button>\r\n					</p>\r\n				</form>\r\n			\r\n				<h3>Dodaj cyfrę</h3>\r\n				<button onclick=\"addChar(document.converter.input, \'1\')\">1</button>\r\n				<button onclick=\"addChar(document.converter.input, \'2\')\">2</button>\r\n				<button onclick=\"addChar(document.converter.input, \'3\')\">3</button>\r\n				<button onclick=\"addChar(document.converter.input, \'4\')\">4</button>\r\n				<button onclick=\"addChar(document.converter.input, \'5\')\">5</button>\r\n				<button onclick=\"addChar(document.converter.input, \'6\')\">6</button>\r\n				<button onclick=\"addChar(document.converter.input, \'7\')\">7</button>\r\n				<button onclick=\"addChar(document.converter.input, \'8\')\">8</button>\r\n				<button onclick=\"addChar(document.converter.input, \'9\')\">9</button>\r\n				\r\n				<button onclick=\"addChar(document.converter.input, \'.\')\">.</button>\r\n			\r\n				<h3>Nowe okno</h3>\r\n				<button onclick=\"openVothcom()\">Otwórz nowe okno</button>\r\n			</article>\r\n		</main>\r\n		\r\n\r\n\r\n\r\n\r\n', 1, 'js'),
(8, 'jquery', '\r\n		\r\n		<main>\r\n			<article id=\"jquery\">\r\n				<header class=\"artheader\">\r\n					<h2>JQuery</h2>\r\n				</header>\r\n					<div id=\"animacjaTestowa1\" class=\"test-block2\"><img style=\"float:left; margin:6px\" src=\"img/q1.jpg\" width=\"450\" height=\"250\" alt=\"jquery\"></div>\r\n\r\n					<div id=\"animacjaTestowa4\" class=\"test-block\">Kliknij, a się powiększę</div>\r\n					   \r\n					<div id=\"animacjaTestowa2\" class=\"test-block\">Najedź kursorem, a się powiększę</div>\r\n				   \r\n					<div id=\"animacjaTestowa3\" class=\"test-block\">Klikaj, abym urósł</div>\r\n			</article>\r\n		</main>\r\n\r\n', 1, 'jq'),
(9, 'filmy', '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/k2IydkL3EOs?si=M72uithppYy4iQKy\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>\r\n\r\n<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/OcwON22ctYc?si=JoQj4lh8khkmbvW3\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>\r\n\r\n<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/tD0Q5QwoQJI?si=JK3h4nZYMMCFLd2t\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>', 1, 'filmy'),
(10, 'panel_admina', '<main>\r\n	<article id=\"admin\">\r\n		<div class=\"topnav\" id=\"myTopnav\">\r\n			<a href=\"index.php?idp=admin&action=list\">Lista podstron</a>\r\n			<a href=\"index.php?idp=admin&action=add\">Dodaj nową podstronę</a>\r\n            <a href=\"index.php?idp=admin&action=category_list\">Lista kategorii</a>\r\n			<a href=\"index.php?idp=admin&action=category_add\">Dodaj nową kategorię</a>\r\n			<a href=\"index.php?idp=admin&action=product_list\">Lista produktów</a>\r\n			<a href=\"index.php?idp=admin&action=product_add\">Dodaj nowy produkt</a>\r\n			<a href=\"index.php?idp=admin&action=logout\">Wyloguj</a>\r\n		</div>\r\n\r\n	</article>\r\n</main>\r\n', 1, 'admin'),
(11, 'kontakt_php', '', 1, 'kontakt_php'),
(12, 'Sklep', '', 1, 'sklep');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `produkty`
--

CREATE TABLE `produkty` (
  `id` int(11) NOT NULL,
  `tytul` varchar(255) NOT NULL,
  `opis` text NOT NULL,
  `data_utworzenia` date NOT NULL,
  `data_modyfikacji` date NOT NULL,
  `data_wygasniecia` date NOT NULL,
  `cena_netto` decimal(10,2) NOT NULL,
  `podatek_vat` int(11) NOT NULL,
  `ilosc` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `kategoria` int(11) NOT NULL,
  `gabaryt` varchar(50) NOT NULL,
  `zdjecie` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produkty`
--

INSERT INTO `produkty` (`id`, `tytul`, `opis`, `data_utworzenia`, `data_modyfikacji`, `data_wygasniecia`, `cena_netto`, `podatek_vat`, `ilosc`, `status`, `kategoria`, `gabaryt`, `zdjecie`) VALUES
(1, 'Lenovo Legion 5', 'Laptop firmy Lenovo', '2024-12-15', '2024-12-15', '2025-01-29', 4000.00, 700, 10, 'dostępny', 4, 'średni', 'uploads/laptop-lenovo-legion-5.png'),
(2, 'Acer Chromebook Plus 515', 'Chromebook marki Acer', '2024-12-15', '2024-12-15', '2025-01-26', 1699.00, 400, 30, 'dostępny', 5, 'średni', 'uploads/chromebook.jpg'),
(3, 'LG UltraGear 24GN65R-B', 'Monitor firmy LG', '2024-12-15', '2024-12-15', '2025-01-16', 599.00, 150, 5, 'dostępny', 6, 'duży', 'uploads/p-lg-24gn60r-b.webp'),
(4, 'Podkładka pod myszkę', 'Podkładka pod myszkę', '2024-12-29', '2024-12-29', '2025-01-31', 50.00, 10, 100, 'dostępny', 8, 'mały', 'uploads/podkladka-pod-mysz-i-nadgarstek-fellowes.jpg'),
(5, 'Laptop ASUS ROG Strix G16', 'laptop ASUS ROG Strix G16', '2025-01-03', '2025-01-04', '2025-02-18', 5000.00, 800, 15, 'dostępny', 4, 'sredni', 'uploads/Laptop-ASUS-ROG-Strix-G16-G614JV-N3134-1.jpg'),
(6, 'Komputer LENOVO Legion T5', '26IRX9 i7-14650HX 32GB RAM 1TB SSD GeForce RTX4060Ti DLSS 3 Wi-Fi Windows 11 Home', '2025-01-06', '2025-01-07', '2025-02-27', 5999.00, 1400, 5, 'dostępny', 2, 'sredni', 'uploads/lenovo-legion-t5-26irb8-intel-core-i5-14400f-32-gb-1tb-ssd-w11h.webp'),
(7, 'Monitor SAMSUNG Odyssey G5', 'LS27CG552EUXEN 27&quot; 2560x1440px 165Hz 1 ms [MPRT] Curved', '2025-01-30', '2025-01-31', '2025-02-19', 699.00, 160, 3, 'dostępny', 7, 'sredni', 'uploads/Monitor-SAMSUNG-Odyssey-G5-LS27CG552EUXEN-01.jpg'),
(8, 'Smartfon SAMSUNG Galaxy S24 Ultra', '5G 12/256GB 6.8&amp;amp;amp;quot; 120Hz Czarny SM-S928', '2024-12-26', '2024-12-26', '2025-02-21', 4999.00, 1150, 25, 'dostępny', 9, 'mały', 'uploads/Smartfon-SAMSUNG-Galaxy-S24-Ultra-Czarny-logotyp.jpg'),
(9, 'Mysz LOGITECH G502 Hero', 'Typ myszy: Optyczna\r\nRozdzielczość: 25600 dpi\r\nKomunikacja z komputerem: Przewodowa\r\nInterfejs: USB\r\nDla graczy: Tak\r\nKolor: Czarny', '2024-12-25', '2024-12-25', '2025-02-28', 179.00, 40, 8, 'dostępny', 8, 'mały', 'uploads/Mysz-LOGITECH-G502-Hero-1.jpg'),
(10, 'Klawiatura MAD DOG GK910K', 'Typ klawiatury: Mechaniczna\r\nKomunikacja z komputerem: Przewodowa\r\nInterfejs: USB\r\nDla graczy: Tak\r\nPodświetlenie: Tak\r\nKolor: Czarny', '2024-12-25', '2024-12-25', '2025-03-12', 199.00, 35, 30, 'dostępny', 8, 'mały', 'uploads/Klawiatura-MAD-DOG-GK910K-Gateron-Red-Czarny-1.jpg'),
(11, 'Smartfon ONEPLUS 12', '12/256GB 5G 6.82&quot; 120Hz Czarny', '2024-12-26', '2024-12-26', '2025-02-20', 2999.00, 690, 5, 'dostępny', 9, 'mały', 'uploads/Smartfon-ONEPLUS-12-5G-Czarny-tyl-front.jpg'),
(12, 'LENOVO IdeaPad Slim 3', 'Chromebook Chrome 14M868 14&quot; MT520 8GB RAM 128GB eMMC Chrome OS', '2024-12-26', '2024-12-26', '2025-02-15', 699.00, 160, 3, 'dostępny', 5, 'średni', 'uploads/Laptop-LENOVO-IdeaPad-Slim-3-01-front.jpg'),
(13, 'Monitor SAMSUNG 24', 'LS24C366EAUXEN 24&amp;quot; 1920x1080px 4 ms [GTG] Curved', '2024-12-26', '2024-12-26', '2025-02-22', 349.00, 100, 7, 'dostępny', 6, 'średni', 'uploads/Monitor-SAMSUNG-LS24C366EAUXEN-front.jpg');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `kategorie`
--
ALTER TABLE `kategorie`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `klienci`
--
ALTER TABLE `klienci`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `koszyki`
--
ALTER TABLE `koszyki`
  ADD PRIMARY KEY (`id`),
  ADD KEY `klient_id` (`klient_id`),
  ADD KEY `produkt_id` (`produkt_id`);

--
-- Indeksy dla tabeli `page_list`
--
ALTER TABLE `page_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alias` (`alias`);

--
-- Indeksy dla tabeli `produkty`
--
ALTER TABLE `produkty`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategoria` (`kategoria`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kategorie`
--
ALTER TABLE `kategorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `klienci`
--
ALTER TABLE `klienci`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `koszyki`
--
ALTER TABLE `koszyki`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `page_list`
--
ALTER TABLE `page_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `produkty`
--
ALTER TABLE `produkty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `koszyki`
--
ALTER TABLE `koszyki`
  ADD CONSTRAINT `koszyki_ibfk_1` FOREIGN KEY (`klient_id`) REFERENCES `klienci` (`id`),
  ADD CONSTRAINT `koszyki_ibfk_2` FOREIGN KEY (`produkt_id`) REFERENCES `produkty` (`id`);

--
-- Constraints for table `produkty`
--
ALTER TABLE `produkty`
  ADD CONSTRAINT `produkty_ibfk_1` FOREIGN KEY (`kategoria`) REFERENCES `kategorie` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
