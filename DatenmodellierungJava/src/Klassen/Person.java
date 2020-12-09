package Klassen;

import java.util.ArrayList;
import java.util.List;

public class Person {
    private int id;   // Deklarierung der benötigten Variablen und Listen für Person (Name, ID und Geschlecht und die Liste für die Freunde und für die gekauften Produkte der Person
    private String name;
    private String geschlecht;
    private List<Person> freundeListe = new ArrayList<>();
    private List<Produkt> gekaufteProdukteListe = new ArrayList<>();


    public void addGekauftesProdukt(Produkt gekauftesProdukt) {   // Funktion, um ein gekauftes Produkt der Liste hinzuzufügen
        this.gekaufteProdukteListe.add(gekauftesProdukt);
    }

    public void addFreunde(Person freund) {   // Funktion, um einen Freund einer Person in die Liste hinzuzufügen
        this.freundeListe.add(freund);
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

    public String getGeschlecht() {
        return geschlecht;
    }

    public void setGeschlecht(String geschlecht) {
        this.geschlecht = geschlecht;
    }

    public List<Person> getFreundeListe() {
        return freundeListe;
    }

    public void setFreundeListe(List<Person> freundeListe) {
        this.freundeListe = freundeListe;
    }

    public List<Produkt> getGekaufteProdukteListe() {
        return gekaufteProdukteListe;
    }

    public void setGekaufteProdukteListe(List<Produkt> gekaufteProdukteListe) {
        this.gekaufteProdukteListe = gekaufteProdukteListe;
    }

    // Konstruktor
    public Person(int id, String name, String geschlecht) {
        this.id = id;
        this.name = name;
        this.geschlecht = geschlecht;
    }
}
