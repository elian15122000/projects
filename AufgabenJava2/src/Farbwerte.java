public class Farbwerte {
    public static void farbwerte(String farbwert) {
        if(farbwert.contains("#")) {  // wenn Farbwert mit einem Hashtag angegeben wird, dann String in zwei Arrays bei Hashtag splitten, um Hashtag "loszuwerden"
            String neuerFarbwert[] = farbwert.split("#");
            String farbe1 = neuerFarbwert[1].substring(0,2);  // zweiten String Array des geteilten Strings nutzen und in drei neue Substrings speichern für die jeweilige Farbe
            String farbe2 = neuerFarbwert[1].substring(2,4);
            String farbe3 = neuerFarbwert[1].substring(4,6);

            int ersteFarbe = Integer.parseInt(farbe1,16);  // Farben in Dezimal konvertieren und ausgeben
            int zweiteFarbe = Integer.parseInt(farbe2,16);
            int dritteFarbe = Integer.parseInt(farbe3,16);

            System.out.println("R: " + ersteFarbe + " G: " + zweiteFarbe + " B: " + dritteFarbe);
        } else  {    // wenn Farbwert ohne Hashtag angegeben wird, dann Farben direkt in neue Substrings speichern, konvertieren und ausgeben
            String farbe1 = farbwert.substring(0,2);
            String farbe2 = farbwert.substring(2,4);
            String farbe3 = farbwert.substring(4,6);

            int ersteFarbe = Integer.parseInt(farbe1,16);
            int zweiteFarbe = Integer.parseInt(farbe2,16);
            int dritteFarbe = Integer.parseInt(farbe3,16);

            System.out.println("R: " + ersteFarbe + " G: " + zweiteFarbe + " B: " + dritteFarbe);
        }

    }
}
