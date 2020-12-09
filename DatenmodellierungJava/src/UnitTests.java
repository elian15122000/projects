/* import Klassen.Person;
import Klassen.Produkt;
import Klassen.Firma;
import org.junit.jupiter.api.Assertions;
import org.junit.jupiter.api.Test;

import java.util.Collections;


//zum Testen sollte man alle Funktionen und Hashmaps in Main von private zu public umwandeln

public class UnitTests {
    @Test
    public void firmenNetzwerkTest() {
        Assertions.assertEquals(Collections.singletonList("Google"), Main.main("--firmennetzwerk=12"));
        Assertions.assertEquals(Collections.singletonList("Apple"), Main.main("--firmennetzwerk=53");
        Assertions.assertEquals(Collections.singletonList("Apple","Google","Samsung"), Main.main("--firmennetzwerk=55");
    }

    @Test
    public void produktNetzwerkTest() {
        Assertions.assertEquals(Collections.singletonList("Google Nexus 5","iPhone","Samsung Chromebook"), Main.main("--produktnetzwerk=150"));
        Assertions.assertEquals(Collections.singletonList("Google Nexus 5","iPad","Macbook Air","Macbook Pro","Samsung Galaxy 5"), Main.main("--produktnetzwerk=20");
        Assertions.assertEquals(Collections.singletonList("Google Nexus 7","iPhone","Macbook Air","Samsung Galaxy Tab 3"), Main.main("--produktnetzwerk=15");
    }

    @Test
    public void personenSucheTest() {
        Assertions.assertEquals(Collections.singletonList("11","James Todd","Male"), Main.main("--personensuche=\"todd\""));
        Assertions.assertEquals(Collections.singletonList("31","Caleb Baker","Male"), Main.main("--personensuche=\"Baker\""));
        Assertions.assertEquals(Collections.singletonList("169","Becky Moody","Female"), Main.main("--personensuche=\"moody\""));
    }
} */
