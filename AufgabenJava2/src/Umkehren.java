public class Umkehren {
    public static String umkehren(String umzukehrendesWort) //Funktion mit String-Parameter
    {
        String umkehren = new String(); // neues String Objekt namens "umkehren"

        for (int i = umzukehrendesWort.length()-1; i >= 0; i--)  // jeden Buchstaben von "umkehren" in umgekehrter Reihenfolge mithilfe von Char pro Zeichn in neuen String "umkehren" speichern
            umkehren += umzukehrendesWort.charAt(i);

        return(umkehren);  // neuer String "umkehren" als Rückgabewert
    }
}
