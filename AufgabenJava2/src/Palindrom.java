public class Palindrom {
    public static boolean palindrom(String palindromPruefung) {   // Funktion mit String-Parameter (bzw. "Eingabe-String")
        palindromPruefung = palindromPruefung.toLowerCase();  // alle Buchstaben von Eingabe-String in Kleinbuchstaben umwandeln um falsche Ausgabe bei Groß- und Kleinbuchstaben zu vermeiden
        char[] inputChars = palindromPruefung.toCharArray();  // Eingabe-String in char-Array speichern
        int inputLength = inputChars.length;
        int inputMid = inputLength / 2;  // Hälfte der Länge des char-Arrays für spätere Prüfung auf Palindrom-Eigenschaft

        for (int i = 0; i <= inputMid; i++) {  // bis Hälfte des char-Arrays zählen und jeweils prüfen, ob Eingabe--String bzw. dessen angelegter char-Array mit "entgegengesetztem" Zeichen gleich ist
            if (inputChars[i] != inputChars[inputLength - i - 1]) {
                return false;  // wenn entgegengesetzte Zeichen nicht gleich, dann false bzw. Eingabe kein Palindrom
            }
        }
        return true;  // Rückgabewert true (Eingabe ist ein Palindrom), wenn Schleife nicht false
    }
}
