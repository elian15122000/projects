import Klassen.Firma;
import Klassen.Person;
import Klassen.Produkt;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;
import java.io.IOException;
import java.util.*;


public class Main {
    private static HashMap<Integer, Person> personen = new HashMap<Integer, Person>();    // Deklarierung von Hashmaps um Personen, Produkte und Firmen inklusive IDs speichern zu können
    private static HashMap<Integer, Firma> firmen = new HashMap<Integer, Firma>();
    private static HashMap<Integer, Produkt> produkte = new HashMap<Integer, Produkt>();
    private static String path = new File("src/projekt.txt").getAbsolutePath();

    public static void main(String[] args) {
        BufferedReader zeilenLeser = null;   // neuer BufferedReader, um die Zeilen einzulesen
        try {
            String aktuelleZeile;     // aktuelle Zeile in den String aktuelleZeile speichern
            int i = 0;     // Zählervariable für new Entity, siehe weiter unten (Erklärung)
            zeilenLeser = new BufferedReader(new FileReader(path));   // die Datei, die ausgelesen werden soll, wird hier angegeben

            while ((aktuelleZeile = zeilenLeser.readLine()) != null) {    // Einlesen beginnt ab hier
                aktuelleZeile = aktuelleZeile.replaceAll("\"","");    // bei der aktuellen Zeile werden die Anführungszeichen durch "nichts" ersetzt, sodass die eingelesene Datei keine Anführungszeichen hat
                String[] neueZeile = aktuelleZeile.split(",");  // neuer String Array, der aktuelleZeile durch das Komma splittet und in den Array einspeichert, sodass man eindeutig die linke von der rechten Spalte(n) unterscheiden und getrennt speichern kann
                if(aktuelleZeile.startsWith("New_Entity")) {   // Ab hier werden die verschiedenen Abschnitte gespeichert, sobald aktuelleZeile mit "New_Entity" startet, wird i um 1 erhöht und dadurch kann später differenziert abgespeichert werden
                    i++;
                    continue;   // continue, um die Zeile mit "New_Entity" zu überspringen und nicht einzuspeichern
                }
                // Abschnitt um die gesamten Daten und Relationen zu speichern bzew. einzulesen
                if(i == 1) {   // wenn i = 1 ist, wird der ganz linke von den 3 Strings genommen und in einen Integeer umgewandelt (ID von der Person)
                    int personId = Integer.parseInt(neueZeile[0]);
                    Person person = new Person(personId, neueZeile[1], neueZeile[2]);   // außerdem wird ein Objekt vom Typ Person erstellt und dieser wird die ID und die zwei anderen Einträge im String Array (von neueZeile, s. oben) gespeichert (Name und Geschlecht) (passiert ja beim einlesen in der Schleife)
                    personen.put(personId, person);  // die gespeicherten Daten werden anschließend in die Hashmap personen gespeichert, somit werden alle Personen inkl. ID abgespeichert
                } else if(i == 2) {    // hier das gleiche Prinzip wie bei der Person, linker Eintrag vor dem Komma wird geparst (ID) und ein neues Produkt erstellt, anschließend in die Hashmap produkte eingepeichert, sodass man alle Produkte gespeichert hat
                    int produktId = Integer.parseInt(neueZeile[0]);
                    Produkt produkt = new Produkt(produktId, neueZeile[1]);
                    produkte.put(produktId, produkt);
                } else if(i == 3) {     // gleiches Verfahren wie bereits beschrieben, sodass die Firmen und dessen IDs in die Hashmap firmen gespeichert werden
                    int firmaId = Integer.parseInt(neueZeile[0]);
                    Firma firma = new Firma(firmaId, neueZeile[1]);
                    firmen.put(firmaId, firma);
                } else if(i == 4) {    // wenn das 4. Mal aktuelleZeile mit "New_Entity" beginnt, werden die Freundschaften gespeichert
                    int personId = Integer.parseInt(neueZeile[0]);   // ID der person (erster Eintrag vom String Array links vom Komma) speichern und parsen
                    int freund = Integer.parseInt(neueZeile[1]);  // ID des freundes (zweiter Eintrag vom String Array rechts vom Komma) speichern und parsen
                    Person person = personen.get(personId);   // neues Objekt vom Typ Person erstellen und die ID zuweisen
                    person.addFreunde(personen.get(freund));   //  der person den Freund zuweisen
                } else if(i == 5) {   // gleiches Verfahren wie bei i = 4 um der Person die gekauften Produkte zuzuweisen
                    int personId = Integer.parseInt(neueZeile[0]);
                    int produktId = Integer.parseInt(neueZeile[1]);
                    Person person = personen.get(personId);
                    person.addGekauftesProdukt(produkte.get(produktId));
                } else if(i == 6) {    // gleiches Verfahren wie bei i = 4 und i = 5 um jedem Produkt eine Firma bzw. dessen Hersteller zuzuweisen
                    int produktId = Integer.parseInt(neueZeile[0]);
                    int firmaId = Integer.parseInt(neueZeile[1]);
                    Produkt produkt = produkte.get(produktId);
                    produkt.addFirma(firmen.get(firmaId));
                }
            }
            // Abschnitt für die Argumente
            if(args.length == 0) {   // Wenn keine Argumente angegeben sind Benutzer sagen, dass nichts angegeben ist
                System.out.println("Nichts angegeben");
            } else if(args.length == 1) {   // Wenn genau ein Argument angegeben ist, prüfe, was die Eingabe ist
                if(args[0].startsWith("--personensuche=")) {  // Falls User Peron suchen möchte
                    String[] personName = args[0].split("=");  // neuer String Array, der "personensuche="name"" von name trennt
                    personName[1] = personName[1].replaceAll("\"",""); // zweiter Eintrag von String Array (ID, rechts nach dem "=")
                    personenSuche(personName[1]);  // rufe die Funktion für die Personensuche auf mit dem zu suchenden namen gegeben vom Benutzer
                } else if(args[0].startsWith("--produktsuche=")) {  // gleiches Verfahren wie bei der Eingabe der Personensuche für die Produktsuche
                    String[] produktName = args[0].split("=");
                    produktName[1] = produktName[1].replaceAll("\"","");
                    produktSuche(produktName[1]);
                } else if(args[0].startsWith("--produktnetzwerk=")) {  // gleiches Verfahren wie bei der Eingabe der Personen- oder Produktsuche mit dem Unterschied, dass nun der zweite Eintrag vo String Array zu einem Integer geparst wird, weil man die ID angibt (Anführungszeichen fallen automatisch weg durch die Eingabe)
                    String[] personProduktName = args[0].split("=");
                    int personId = Integer.parseInt(personProduktName[1].replaceAll("\"",""));
                    produktNetzwerk(personId);
                } else if(args[0].startsWith("--firmennetzwerk=")) {    // gleiches Verfahren wie bei der Eingabe von der Produktsuche auch mit parsen vom String
                    String[] produktFirmaName = args[0].split("=");
                    int personId = Integer.parseInt(produktFirmaName[1].replaceAll("\"",""));
                    firmenNetzwerk(personId);
                } else {
                    System.out.println("Falsche Eingabe");  // Falls nicht eine von den vier verschiedenen Suchen angegeben ist, melde dem Nutzer zurück, dass die Eingabe fehlerhaft ist
                }
            } else {
                System.out.println("Zu viele Argumente");  // Falls mehr als ein Argument angegeben wird, wird dies dem Nutzer zurückgemeldet
            }
        } catch (IOException e) {   // wenn es einen Fehler gibt, dann wird der Fehler ausgegeben
            e.printStackTrace();
        } finally {    // Beende Einlesen wenn es einen Fehler gibt
            try {
                if (zeilenLeser != null)
                    zeilenLeser.close();
            } catch (IOException ex) {
                ex.printStackTrace();
            }
        }
    }

    private static void personenSuche(String name) {  // Funktion für die Personensuche
        int j = 0;
        while(j < personen.size()) {    // j hochzählen um jede ID zu bekommen bis Ende der Hashmap
            String gesuchtePerson = personen.get(j).getName().toLowerCase();  //  neuer String erstellen für Groß- und Kleinschreibung und den Namen der Person bekommen und mit toLowerCase in Kleinbuchstaben umwandeln (um Groß und Kleinschreibung bei Suche zu ignorieren)
            if(gesuchtePerson.contains(name.toLowerCase())) {  // wenn die Person j die Person ist bzw. den gleichen Namen hat (enthält), die vom User eingegeben wurde, dann printe den Namen, das Geschlecht und dessen ID
                System.out.println("Personenname: " + personen.get(j).getName() + ", Geschlecht: " + personen.get(j).getGeschlecht() + ", ID: " + j);
            }
            j++; // j um eins erhöhren um nächste Person in Hashmap mit der gesuchten Person zu vergleichen bzw dessen Namen zu vergleichen
        }
    }

    private static void produktSuche(String name) {  // Funktion für Produktsuche, funktioniert genau gleich wie die Personensuche, bloß mit Produkten (siehe personenSuche())
        int j = 203;  // da die Produkte erst ab der ID 203 starten, ist die Zählervariable zu Beginn schon 203 und läuft dann bis 203 + die Länge der Einträge in der Hashmap produkte
        int k = 203 + produkte.size();
        while(j < k) {
            String gesuchtesProdukt = produkte.get(j).getName().toLowerCase();
            if(gesuchtesProdukt.contains(name.toLowerCase())) {
                System.out.println("Produktname: " + produkte.get(j).getName() + ", ID: " + j);
            }
            j++;
        }
    }

    private static void produktNetzwerk(int id) {  // Funktion für Produktnetzwerk
        List<String> produktListe = new ArrayList<>();  // neue String Arraylist erstellen, um die gekauften Produkte zu speichern
        for(Person freund : personen.get(id).getFreundeListe()) {  // für jeden Freund der gegebenen ID bekommt man durch die for Schleife in der foreach Schleife die Produkte und fügt sie der Liste produktListe hinzu, falls diese noch nicht den gleichen Eintrag hat
            for(int j = 0; j < freund.getGekaufteProdukteListe().size(); j++) {
                if (!produktListe.contains(freund.getGekaufteProdukteListe().get(j).getName())) {
                    produktListe.add(freund.getGekaufteProdukteListe().get(j).getName());
                }
            }
        }
        for(int i = 0; i < personen.get(id).getGekaufteProdukteListe().size(); i++) {
            if(produktListe.contains(personen.get(id).getGekaufteProdukteListe().get(i).getName())) {  // Überprüfung, ob die produktListe der Freunde Produkte enthält, die die gegebene ID (Person) gekauft hat, also man nimmt hier alle Produkte der Person und gleicht sie mit der Liste ab
                produktListe.remove(personen.get(id).getGekaufteProdukteListe().get(i).getName());  // Falls ein Produkt das gleiche ist wie in der Liste, so soll dieses Produkt aus der Liste entfernt werden
            }
        }
        Collections.sort(produktListe, String.CASE_INSENSITIVE_ORDER);  // Alphabetische Ordnung für die Liste
        String liste = Arrays.toString(produktListe.toArray()).replace("[", "").replace("]", "").replace(", ", ",");;  // Liste von "[" und "]" ersetzen durch nichts, sodass sie nicht ausgegeben werden, ebenfalls Leerzeichen nach Komma entfernen
        System.out.println(liste);  // Ausgabe der Liste (bzw. liste wegen den replacements) bzw. Ausgabe endgültiges Produknetzwerks
    }

    private static void firmenNetzwerk(int id) {    // Funktion für Firmennetzwerk
        List<String> firmenListe = new ArrayList<>();  // String Arraylist für die Firmen
        for(Person freund : personen.get(id).getFreundeListe()) {   // Für jeden Freund der gegebenen Person werden die Firmen bzw Hersteller der gekauften Produkte in die Arraylist gespeichert
            for(int j = 0; j < freund.getGekaufteProdukteListe().size(); j++) {
                if(!firmenListe.contains(freund.getGekaufteProdukteListe().get(j).getHerstellerListe().get(0).getName())) {  //  get(0) weil es für jedes Produkt nur genau einen Hersteller bzw Firma gibt
                    firmenListe.add(freund.getGekaufteProdukteListe().get(j).getHerstellerListe().get(0).getName());
                }
            }
        }
        for(int i = 0; i < personen.get(id).getGekaufteProdukteListe().size(); i++) {
            if(firmenListe.contains(personen.get(id).getGekaufteProdukteListe().get(i).getHerstellerListe().get(0).getName())) {  // Überprüfung, ob firmenListe Firmen enthält, von denen die gegebene Person auch etwas gekauft hat
                firmenListe.remove(personen.get(id).getGekaufteProdukteListe().get(i).getHerstellerListe().get(0).getName());   //  Falls dies der Fall ist, wird diese Firma aus der Firmenliste entfernt
            }
        }
        Collections.sort(firmenListe, String.CASE_INSENSITIVE_ORDER);  // Alphabetische Ordnung für die Liste
        String liste = Arrays.toString(firmenListe.toArray()).replace("[", "").replace("]", "").replace(", ", ","); // Liste von "[" und "]" ersetzen durch nichts, sodass sie nicht ausgegeben werden, ebenfalls Leerzeichen nach Komma entfernen
        System.out.println(liste);   // Ausgabe firmenListe (bzw. liste wegen den replacements) bzw. Ausgabe endgültiges FirmenNetzwerk
    }
}