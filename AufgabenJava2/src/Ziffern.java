public class Ziffern {
    public static void ziffern(String zeichenkette) {
        String ziffernInZeichenkette =  zeichenkette.replaceAll("[^0-9]", "");  // neuer String durch Ersetzen aller Zeichen außer 0 bis 9 mit einem Leerzeichen, sodass nur die Ziffern in dem anfangs gegebenen String übrig bleiben
        int anzahlZiffern;
        anzahlZiffern = ziffernInZeichenkette.length();  // Variable vom typ Integer für die Anzahl der Ziffern durch das Zählen des neuen Strings der nur die Ziffern enthält (mihilfe von .length())


        if(ziffernInZeichenkette == zeichenkette && ziffernInZeichenkette.matches(".*\\d.*")) {  // wenn gegebener String gleich ist wie der neue String, der nur die gefilterten Ziffern enthält, so hat der gegebene String auch nur Ziffern. Dies ausgeben mit der Anzahl
            System.out.println("Die eingegebene Zeichenkette beinhaltet nur Ziffern: " + ziffernInZeichenkette + " mit der Anzahl von " + anzahlZiffern + " Ziffern.");
        } else {  // wenn gegebener String nicht gleich ist wie der neue String mit den gefilterten Ziffern, dann nur die enthaltenen Ziffern plus Anzahl angeben
            System.out.println("Die eingegebene Zeichenkette beinhaltet folgende Ziffern: " + ziffernInZeichenkette + " mit der Anzahl von " + anzahlZiffern + " Ziffern.");
        }
    }
}
