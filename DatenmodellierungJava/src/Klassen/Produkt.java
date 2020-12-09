package Klassen;

import java.util.ArrayList;
import java.util.List;

public class Produkt {

    private int id;
    private String name;
    private List<Firma> herstellerListe = new ArrayList<>();
    private List<Produkt> hergestellteProdukteListe = new ArrayList<>();


    public void addFirma(Firma hersteller) {  // Funktion, um Firma bzw. Hersteller des Produkts in Liste hinzuzufügen
        this.herstellerListe.add(hersteller);
    }


    // Getter und Setter
    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public List<Firma> getHerstellerListe() {
        return herstellerListe;
    }

    public void setHerstellerListe(List<Firma> herstellerListe) {
        this.herstellerListe = herstellerListe;
    }

    public List<Produkt> getHergestellteProdukteListe() {
        return hergestellteProdukteListe;
    }

    public void setHergestellteProdukteListe(List<Produkt> hergestellteProdukteListe) {
        this.hergestellteProdukteListe = hergestellteProdukteListe;
    }

    //Konstruktor
    public Produkt(int id, String name) {
        this.id = id;
        this.name = name;
    }
}
