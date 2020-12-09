public class Palindrom {
    public static boolean palindrom(String palindromPruefung) {   // Funktion mit String-Parameter (bzw. "Eingabe-String")
        palindromPruefung = palindromPruefung.toLowerCase();  // alle Buchstaben von Eingabe-String in Kleinbuchstaben umwandeln um falsche Ausgabe bei Gro�- und Kleinbuchstaben zu vermeiden
        char[] inputChars = palindromPruefung.toCharArray();  // Eingabe-String in char-Array speichern
        int inputLength = inputChars.length;
        int inputMid = inputLength / 2;  // H�lfte der L�nge des char-Arrays f�r sp�tere Pr�fung auf Palindrom-Eigenschaft

        for (int i = 0; i <= inputMid; i++) {  // bis H�lfte des char-Arrays z�hlen und jeweils pr�fen, ob Eingabe--String bzw. dessen angelegter char-Array mit "entgegengesetztem" Zeichen gleich ist
            if (inputChars[i] != inputChars[inputLength - i - 1]) {
                return false;  // wenn entgegengesetzte Zeichen nicht gleich, dann false bzw. Eingabe kein Palindrom
            }
        }
        return true;  // R�ckgabewert true (Eingabe ist ein Palindrom), wenn Schleife nicht false
    }
}
