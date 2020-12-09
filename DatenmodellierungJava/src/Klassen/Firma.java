package Klassen;

public class Firma {

    private int id;
    private String name;

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

    // Konstruktor
    public Firma(int id, String name) {
        this.id = id;
        this.name = name;
    }
}
