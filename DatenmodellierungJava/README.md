# Datenmodellierung / -suche Java
Eine gegebene Textdatei mit verketteten ID's und Namen von Personen und Firmen (inkl. Produkte) wird eingelesen und der User kann nach gewünschten Verknüpfungen suchen bzw. seine Suche.
Eingabe erfolgt in der Konsole mit folgenden Schlagwörtern bzw. folgende Suchen sind möglich:<br>
<ul>
  <li>Firmennetzwerk: Anhand gegebener Personen-ID alle Firmen ausgeben, die die Produkte hergestellt haben, welche von den Freunden der Personen-ID gekauft wurden<br>
  Konsole: --firmennetzwerk=gewünschteID </li>
  <li>Produktnetzwerk: Anhand gegebener Personen-ID alle gekauften Produkte der Freunde dieser Person ausgeben<br>
  Konsole: --produktnetzwerk=gewünschteID</li>
  <li>Person: Person kann anhand Vor- oder / und Nachname gesucht werden, Name etc. wird ausgegeben<br>
  Konsole: --personensuche="gewünschterName"</li>
  <li>Produkt: Produkt kann anhand Name gesucht werden, ID etc. wird ausgegeben<br>
  Konsole: --produktsuche="gewünschterName"</li>
</ul>
