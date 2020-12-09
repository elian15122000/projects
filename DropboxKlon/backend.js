var object;
var timeout;

function timer() {  // Funktion für automatischen Logout
    window.clearTimeout(timeout);  // Lösche den Inhalt von Timeout, wenn Funktion aufgerufen wird, sodass der Timeout wieder neu auf 10 Minuten gesetzt werden kann (s. weiter unten)
    timeout = setTimeout(function(){ 
        logout();
        alert("Automatischer Logout wegen Inaktivität.");
    }, 600000);
}

function login() {                          //Anfrage für Login mit Value von Input für Username und Passwort mit der id username und password
    var req = new XMLHttpRequest();
    var formData = new FormData();
    formData.append("username", document.getElementById("username").value);
    formData.append("password", document.getElementById("password").value);

    req.open("POST", "http://localhost:8080/login", true);
    req.setRequestHeader("Accept", "text/json");
    req.onreadystatechange = function (event) {
        if (this.readyState == 4) {
            if (this.status == 200) {    // Wenn Anfrage erfolgreich war (beide Felder mit "admin" ausgefüllt), dann erstelle den Authentifizierungscode (Base 64 Token) und speicher ihn in den Session Storage, damit die anderen Funktionen bzw Anfragen damit "arbeiten" können
                sessionStorage.setItem("authcode", "Basic " + btoa(formData.get("username") + ":" + JSON.parse(this.response).token));

                document.getElementById("page").style.display = "inline";  // Wenn Anfrage erfolgreich, dannsetze das div mit der id page auf sichtbar und verstecke login Abschnitt mit id login
                document.getElementById("login").style.display = "none";
                sessionStorage.setItem("path", "");   // Setze den path in den Session Storage auf "", also leer, da man sich nach dem Login in keinem Unterordner befindet
                document.getElementById("logout").style.cursor = "pointer";   // Setze pointer von Logout Button auf pointer, da man sich nur auf der Hauptseite ausloggen kann
                getData(""); // Rufe die Funktion getData("") auf, damit alle Daten aus dem Wurzelverzeichnis über getOrdner geholt werden --> warum nicht direkt über getOrdner? --> Wegen des Pfades für die History API

            } else {  // Wenn Anfrage nicht erfolgreich war, melde User per alert zurück, dass (je nach Eingabe) Pw, Username oder beides falsch waren
                if ((document.getElementById("username").value != "admin") && (document.getElementById("password").value == "admin")) {
                    alert("Falschen Usernamen eingegeben!");
                } else if ((document.getElementById("password").value != "admin") && (document.getElementById("username").value == "admin")) {
                    alert("Falsches Passwort eingegeben!");
                } else {
                    alert("Passwort und Username falsch!");
                }
            }
        }
    }
    req.send(formData);
}

function logout() {    // Anfrage für Logout, wenn Button Logout geklickt wird 
    var req = new XMLHttpRequest();
    req.open("GET", "http://localhost:8080/logout", true);
    req.setRequestHeader("Authorization", sessionStorage.getItem("authcode"));  // Sicherstellung von Übereinstimmung des Tokens bzw dass der gleiche User sich ausloggt, der sich auch eingeloggt hat
    req.onreadystatechange = function (event) {
        if (this.readyState == 4) {
            if (this.status == 200) {   // Wenn Logout erfolgreich, dann setze Container mit Id page af nicht sichtbar und setze den Login Container wieder auf sichtbar
                sessionStorage.clear();   // clear bei erfolgreichem Logout alle sicherheitsrelkevanten Daten (alle Daten) aus dem Session Storage
                document.getElementById("page").style.display = "none";
                document.getElementById("login").style.display = "inline";
                document.getElementById("ordnercontent").innerHTML = "";  // Setze den Container Ordnercontent, in den alle neu erzeugten Divs gespeichert werden, wieder auf ""
            } else {
                alert("Der Logout hat leider nicht funktioniert.");   // Rückmeldung an User, falls Logout nicht erfolgreich war
            }
        }
    }
    req.send();
}

function showType(type) {   // Funktion für das Anzeigen von den verschiedenen Icons, abhängig vom Typ, der als Parameter später mitgegeben wird 
    var icon;
    if (type.startsWith("dir")) {
        return icon = "/assets/ordner.png";
    } else if (type.startsWith("image")) {
        return icon = "/assets/bild.png";
    } else if (type.startsWith("text")) {
        return icon = "/assets/text.png";
    } else if (type.startsWith("video")) {
        return icon = "/assets/video.png";
    } else if (type.startsWith("audio")) {
        return icon = "/assets/audio.png";
    } else {
        return icon = "/assets/rest.png";
    }
}

function addOrdner() {   // Anfrage, um Ordner hinzuzufügen in einen (aktuellen) path (dieser ist ja immer im Session Storage) --> wird aufgerufen, wenn User über Button den Dialog mit dem Input öffnet 
    var path = sessionStorage.getItem("path");  // Path aus Session Storage in Variable path speichern (für die Übersicht)
    let formData = new FormData();
    formData.append('type', 'dir');
    var req = new XMLHttpRequest();

    req.open("POST", "http://localhost:8080/" + path + "/" + document.getElementById("ordnername").value, true);  // Ordner in den aktuellen Path speichern
    req.setRequestHeader("Authorization", sessionStorage.getItem("authcode"));
    req.onreadystatechange = function (event) {
        if (this.readyState == 4) {
            if (this.status == 200) {
                timer();  // Tiimer resetten wegen Anfrage
                ordnername = document.getElementById("ordnername").value;
                addOrdnerElement(ordnername);  // Ordner erstellen bzw. anzeigen mit dem eingegebenen Namen für den Ordner vom User (s. weiter unten)
            } else {
                alert("Hinzufügen des Ordners fehlgeschlagen!");  // Fehlermeldung, falls Hinzufügen des Ordners nicht klappt (zb falls es im gleichen Verzeichnis (Path) einen gleich benannten Ordner gibt)
            }
        }
    }
    req.send(formData);
}

function getOrdner(path) {  // Bekommt path und lädt allgemein dessen Ordner bzw. Verzeichnis 
    var path = sessionStorage.getItem("path");  // neue Variable path erstellen und dieser den Path aus dem Session Storage zuweisen (Für die Übersicht)
    let formData = new FormData();
    formData.append('type', 'dir');
    var req = new XMLHttpRequest();

    req.open("GET", "http://localhost:8080/" + path, true);  // Bekomme das den Path bzw dessen Ordner
    req.setRequestHeader("Authorization", sessionStorage.getItem("authcode"));  // Überprüfung der Autorisierung bzw. wie bei allen Anfragen Sicherstellung, dass User wirklich der User ist, der sich eingeloggt hat
    req.onreadystatechange = function (event) {
        if (this.readyState == 4) {
            if (this.status == 200) {
                timer();  // Timer resetten über Funktion timer(), da neue Anfrage
                let response = JSON.parse(this.responseText);   // responseText (die Ordner, die man bekommen hat) vom Webservice als JSON parsen
                drawOrdner(response);  // rufe Funktion drawOrdner mit dem Parameter responseText, der in response gespeichert und als JSON geparst wurde, auf --> s. unten (stellt Ordner mit Unterordnern etc dar im HTML)
                document.getElementById("verzeichnis").innerText = path + "/";  // setze den Text von H1 im HTML mit der Id "Verzeichnis" auf "/" (wird später verändert in den anderen Funktionen)
                if (sessionStorage.getItem("path") == "") {   // Wenn der Path "" ist, dann kann man sich ausloggen und der Logoutbutton ist klickbar, ansonsten wenn man nicht auf der Startseite bzw im Root Verzeichnis ist, soll der Button disabled sein, sodass man sich nicht ausloggen kann
                    document.getElementById("logout").style.cursor = "pointer";
                    document.getElementById("logout").removeAttribute("disabled");
                } else {
                    document.getElementById("logout").style.cursor = "not-allowed";
                    document.getElementById("logout").setAttribute("disabled", "");
                }
            } else {
                alert("Ordner konnte nicht bekommen werden.");  // Falls Anfrage für getOrdner fehlschlägt, dies dem User zurückmelden
            }
        }
    }
    req.send(formData);
}
function getData() {  // Anfrage, um die Daten aus dem Root Folder zu bekommen (somit umgeht man hier das Problem, dass man auch zurück in den Root Folder navigieren kann mit der History API)
    path = sessionStorage.getItem("path");
    var formdata = new FormData();
    var request = new XMLHttpRequest();
    request.open("GET", "http://localhost:8080/" + path, true);  // Anfrage für Path
    request.setRequestHeader("Authorization", sessionStorage.getItem("authcode"));
    request.onreadystatechange = function (event) {
        if (this.readyState == 4) {
            if (this.status == 200) {
                if (sessionStorage.getItem("path") == "") { // Wenn der Path "" ist, dann kann man sich ausloggen und der Logoutbutton ist klickbar, ansonsten wenn man nicht auf der Startseite bzw im Root Verzeichnis ist, soll der Button disabled sein, sodass man sich nicht ausloggen kann
                    document.getElementById("logout").style.cursor = "pointer";
                    document.getElementById("logout").removeAttribute("disabled");
                } else {
                    document.getElementById("logout").style.cursor = "not-allowed";
                    document.getElementById("logout").setAttribute("disabled", "");
                }
                history.pushState({ dir: "" }, "Root", "");
                getOrdner(path);   // Ordner vom Path laden 
                timer();  // Timer neu setzen, da Anfrage
            } else {
                alert("Daten konnten nicht geholt werden."); // Rückmeldung, falls Anfrage scheitert 
            }
        }
    }
    request.send(formdata);
}

function getPushstateOrdner(path) {  // gleiche Anfrage wie get Ordner, mit dem Unterschied, dass diese hier nur die History API nutzt beim popstate
    let formData = new FormData();   // würde man getOrdner bei pushState nutzen und nicht diese Funktion, dann würden die Ordner beim navigieren weiterhin angezeigt werden und dieses Problem umgeht man hiermit
    formData.append('type', 'dir');
    var req = new XMLHttpRequest();

    req.open("GET", "http://localhost:8080/" + path, true);
    req.setRequestHeader("Authorization", sessionStorage.getItem("authcode"));
    req.onreadystatechange = function (event) {
        if (this.readyState == 4) {
            if (this.status == 200) {
                timer();  // timer resetten
                let response = JSON.parse(this.responseText); 
                document.getElementById("ordnercontent").innerText = "";   // Ordnercontent div leeren 
                drawOrdner(response);  // Ordner anzeigen aus der response 
                document.getElementById("verzeichnis").innerText = path + "/";  // das Verzeichnis dem User richtig anzeigen
            } else {
                alert("Ordner nicht gefunden beim Navigieren");  //Rückmeldung, falls Anfrage nicht geklappt hat
            }
        }
    }
    req.send(formData);
}

var ordnercontent = document.createElement("div");  // neues Div erstellen, um später in dieses Div alle neu geladenen Ordner und Files zu "speichern" bzw anzuzeigen

function drawOrdner(JsonObjekt) {  //Funktion, um Ordner anzuzeigen auf der Seite bzw JsonObjekt in HTML zu "konvertieren"

    for (let i = 0; i < JsonObjekt.length; i++) {  // Alle Objekte aus dem JSON Array bzw Objekt durchgehen und für jedes Objekt 
        ordnercontent.id = "ordnercontent";   // gebe dem oben erstellten, übergeordneten Div die id "ordnercontent"
        var ordnername = JsonObjekt[i].Name;  // neue Variable, in der der i-te Name (wird vom JSON Objekt mitgeliefert) gespeichert wird
        var type = JsonObjekt[i].Type;  // das Gleiche mit dem i-ten Type zum zugehörigen i-ten Namen

        var newDiv = document.createElement("div");  // neues Div erstellen und dem ordnercontent div appenden, sodass das neue Div dem div ordnercontent untergeordnet ist
        ordnercontent.appendChild(newDiv);
        var img = document.createElement("img");  // neues Image für das Icon erstellen
        var br = document.createElement("BR");   // Zeilenumbruch erstellen (geeignet für zwischen Image und Button)
        img.src = showType(type);  // Funktion für Anzeige der Icons aufrufen mit dem Parameter type (s. oben) --> je nach type von dem Element wird das zugehörige Icon angezeigt
        var newContent = document.createElement("h1");  // H1 Element erstellen und dem newDiv unterordnen für die Anzeige des Namens der Datei / des Ordners
        newContent.classList.add("h1");
        newContent.innerHTML = ordnername;

        newDiv.appendChild(newContent);  // Alle Elemente in der richten Reihenfolge dem newDiv appenden
        newDiv.appendChild(img);
        newDiv.appendChild(br);
        newDiv.id = ordnername;
        newDiv.classList.add("daten");  // für das Styling später die Klasse daten zu newDiv hinzufügen (also jedem newDiv, weil wir noch immer in der Schleife drinnen sind)
        newDiv.type = type;

        img.setAttribute("data-ordner", ordnername);   // img die Attribute Name und Typ zuweisen, sodass man auf diese später zugreifen kann und Klasse geben für das spätere Styling der Icons
        img.setAttribute("data-type", type);
        img.classList.add("img");


        var deleteButton = document.createElement("button");  // Löschen Button erstellen, um Ordner und Files direkt wieder löschen zu können
        deleteButton.innerHTML = "delete " + img.getAttribute("data-ordner");  
        deleteButton.classList.add("deletebutton");

        deleteButton.setAttribute("data-ordner", ordnername);  // Button ebenfalls Name und Typ als Attribut mitgeben 
        deleteButton.setAttribute("data-type", type);

        newDiv.setAttribute("data-ordner", ordnername);  // newDiv auch Name und Typ als Attribut mitgeben
        newDiv.setAttribute("data-type", type);

        newDiv.appendChild(deleteButton);  // Button dem Div hinzufügen
        document.getElementById("page").appendChild(ordnercontent); // ordnercontent div dem page div hinzufügen
        document.getElementById("ordnercontent").appendChild(newDiv);  // newDiv dem ordnercontent div hinzufügen 

        deleteButton.addEventListener("click", function () {  // Falls Delete Button geklickt wird, dann lösche mit der Funktion deleteOrdner den Ordner (deleteOrdner kann auch Dateien löschen)
            deleteOrdner(this.getAttribute("data-ordner"));
        });


        img.addEventListener("click", function () {   // Pürfen, welches Icon geklickt wurde und welchen Type es hat 
            if (this.getAttribute("data-type") == "dir") {  // Wenn der Type dir ist, dann setze den Path neu mit dem Ordnernamen und speichere die History, setze alle Elemente auf none und bekomme den Ordner mit getOrdner(path) und setze seine Inhalte auf inline (sichtbar)
                path = sessionStorage.getItem("path");
                sessionStorage.setItem("path", path + "/" + this.getAttribute("data-ordner"));
                const state = { 'dir': sessionStorage.getItem("path") };
                window.history.pushState(state, sessionStorage.getItem("path"), sessionStorage.getItem("path"));
                document.getElementById("back").removeAttribute("disabled");  // Der Zurück Button kann nunr geklickt werden, da man nicht mehr im Root Ordner ist
                document.getElementById("back").style.cursor = "pointer";
                getOrdner(path);
                for (var i = 0; i < ordnercontent.childNodes.length; i++) {
                    if (ordnercontent.childNodes[i] == this.getAttribute("data-ordner")) {
                        ordnercontent.childNodes[i].style.display = "inline";
                    } else {
                        ordnercontent.childNodes[i].style.display = "none";
                    }
                }
            } else {   // nun ist sichergestellt, dass es sich um eine Datei handelt, falls typ != dir ist
                document.getElementById("back").removeAttribute("disabled"); // Der Zurück Button kann nunr geklickt werden, da man nicht mehr im Root Ordner ist
                document.getElementById("back").style.cursor = "pointer";
                path = sessionStorage.getItem("path");  // Bekomme den path und speicher ihn in eine Variable 
                sessionStorage.setItem("path", path + "/" + this.getAttribute("data-ordner"));  // Setze den Path neu und erweitere ihn um den Namen der Datei 
                const state = { 'dir': sessionStorage.getItem("path") };
                window.history.pushState(state, sessionStorage.getItem("path"), sessionStorage.getItem("path")); //speichere path in history
                if (this.getAttribute("data-type").startsWith("audio")) { // Wenn Typ Audio ist, rufe die Funktion getAudio(name) auf, um Datei anzuzeigen und Download Button anzuzeigen, setze den Rest auf display = "none"
                    getAudio(this.getAttribute("data-type"), this.getAttribute("data-ordner"));
                    for (var i = 0; i < ordnercontent.childNodes.length; i++) {
                        if (ordnercontent.childNodes[i] == this.getAttribute("data-ordner")) {
                            ordnercontent.childNodes[i].style.display = "inline";
                        } else {
                            ordnercontent.childNodes[i].style.display = "none";
                        }
                    }
                } else if (this.getAttribute("data-type").startsWith("image")) { // Wenn TypImage ist, rufe die Funktion getImage(name) auf, um Datei anzuzeigen und Download Button anzuzeigen, setze den Rest auf display = "none"
                    getImage(this.getAttribute("data-type"), this.getAttribute("data-ordner"));
                    for (var i = 0; i < ordnercontent.childNodes.length; i++) {
                        if (ordnercontent.childNodes[i] == this.getAttribute("data-ordner")) {
                            ordnercontent.childNodes[i].style.display = "inline";

                        } else {
                            ordnercontent.childNodes[i].style.display = "none";
                        }
                    }
                } else if (this.getAttribute("data-type").startsWith("video")) { // Wenn Typ Video ist, rufe die Funktion getVideo()name auf, um Datei anzuzeigen und Download Button anzuzeigen, setze den Rest auf display = "none"
                    getVideo(this.getAttribute("data-type"), this.getAttribute("data-ordner"));
                    for (var i = 0; i < ordnercontent.childNodes.length; i++) {
                        if (ordnercontent.childNodes[i] == this.getAttribute("data-ordner")) {
                            ordnercontent.childNodes[i].style.display = "inline";
                        } else {
                            ordnercontent.childNodes[i].style.display = "none";
                        }
                    }
                } else if (this.getAttribute("data-type").startsWith("text")) { // Wenn Typ Audio ist, rufe die Funktion getText(name) auf, um Datei anzuzeigen und Download Button anzuzeigen, setze den Rest auf display = "none"
                    getText(this.getAttribute("data-type"), this.getAttribute("data-ordner"));
                    for (var i = 0; i < ordnercontent.childNodes.length; i++) {
                        if (ordnercontent.childNodes[i] == this.getAttribute("data-ordner")) {
                            ordnercontent.childNodes[i].style.display = "inline";
                        } else {
                            ordnercontent.childNodes[i].style.display = "none";
                        }
                    }
                }
            }
        });
    }
    if (sessionStorage.getItem("path") == "") {  // Wenn der Path "" ist, dann kann man sich ausloggen und der Logoutbutton ist klickbar, ansonsten wenn man nicht auf der Startseite bzw im Root Verzeichnis ist, soll der Button disabled sein, sodass man sich nicht ausloggen kann
        document.getElementById("logout").style.cursor = "pointer";
        document.getElementById("logout").removeAttribute("disabled");
    } else {
        document.getElementById("logout").style.cursor = "not-allowed";
        document.getElementById("logout").setAttribute("disabled", "");
    }
}

function uploadData(file) {  // Anfrage für den Upload, wurde aber noch nicht richtig implementiert leider (also einfach ignorieren)
    var formData = new FormData();
    var req = new XMLHttpRequest();
    formData.append('newFile', '@local/File');
    req.open("POST", "http://localhost:8080/" + path, true);
    req.setRequestHeader("Authorization", authcode);
    req.onreadystatechange = function (event) {
        if (this.readyState == 4) {
            if (this.status == 200) {
                timer();
                file = document.getElementById(file).value;
            } else {
                alert("Upload hat nicht funktioniert!");
            }
        }
    }
    req.send(formData)
}


function deleteOrdner(ordnername) {  // Anfrage, um Ordner (und auch Datei) aus Path (Verzeichnis) zu löschen, da beide Anfragen gleich sind
    let formData = new FormData();
    var req = new XMLHttpRequest();
    path = sessionStorage.getItem("path");
    req.open("DELETE", "http://localhost:8080" + path + "/" + ordnername, true);
    req.setRequestHeader("Authorization", sessionStorage.getItem("authcode"));
    req.onreadystatechange = function (event) {
        if (this.readyState == 4) {
            if (this.status == 200) {
                timer(); // Timer neu setzen wegwn Anfrage
                document.getElementById(ordnername).style.display = "none";  // Element nicht mehr auf Benutzeroberfläche anzeigen
            } else {
                alert("Löschen fehlgeschlagen. Bitte überprüfen, ob im Ordner noch Dateien vorhanden sind.");  // Rückmeldung, falls Löschen nicht geklappt hat
            }
        }
    }
    req.send();
}

function getImage(datatype, dataname) {  // Funktion, um Bild anzeigen zu lassen mit Parametern type und name
    var formdata = new FormData();
    formdata.append('type', 'image')
    var request = new XMLHttpRequest();
    request.open("GET", "http://localhost:8080/" + path + "/" + dataname + "?format=base64", true);  // Anfrage für Base64 Format
    request.setRequestHeader("Authorization", sessionStorage.getItem("authcode"));
    request.onreadystatechange = function (event) {
        if (this.readyState == 4) {
            if (this.status == 200) {
                timer();  // Timer wegen Anfrage resetten
                document.getElementById("verzeichnis").innerText = path + "/" + dataname; //Verzeichnis richtig für User anzeigen
                var image = document.createElement("img");  // neues img Element erstellen 
                var br = document.createElement("BR");
                image.id = "image";
                var dataurl = "data:" + datatype + ";base64," + this.responseText; //dataurl mithilfe von Response erstellen, und diese als source in image speichern, um Bild anzuzeigen
                image.src = dataurl;
                image.setAttribute("width", "70%");  // Image 70% Breite geben
                ordnercontent.appendChild(image);  // Image, Zeilenumbruch und "Button" (eigentlich ein Link für den Download) dem ordnercontent div hinzufügen
                ordnercontent.appendChild(br);
                var downloadButton = document.createElement("a");
                downloadButton.innerHTML = "Download &#8595;";
                downloadButton.classList.add("downloadbutton");
                downloadButton.setAttribute("download", "");
                downloadButton.setAttribute("href", dataurl);   // Attribut href="dataurl" geben, damit man das Bild downloaden kann
                ordnercontent.appendChild(downloadButton);
            } else {
                alert("Datei kann nicht angezeigt werden!");
            }
        }
    }
    request.send();
}

function getVideo(datatype, dataname) {  // funktioniert genau gleich wie Image, bloß mit Video Element 
    var formdata = new FormData();
    formdata.append('type', 'video')
    var request = new XMLHttpRequest();
    request.open("GET", "http://localhost:8080/" + path + "/" + dataname + "?format=base64", true);
    request.setRequestHeader("Authorization", sessionStorage.getItem("authcode"));
    request.onreadystatechange = function (event) {
        if (this.readyState == 4) {
            if (this.status == 200) {
                timer();
                document.getElementById("verzeichnis").innerText = path + "/" + dataname;
                var video = document.createElement("video");
                var br = document.createElement("BR");
                video.id = "video";
                var source = document.createElement("source");
                var dataurl = "data:" + datatype + ";base64," + this.responseText;
                video.setAttribute("controls", "");
                video.setAttribute("width", "70%");
                source.src = dataurl;
                source.type = datatype;
                video.appendChild(source);
                ordnercontent.appendChild(video);
                ordnercontent.appendChild(br);

                var downloadButton = document.createElement("a");
                downloadButton.innerHTML = "Download &#8595;";
                downloadButton.classList.add("downloadbutton");
                downloadButton.setAttribute("download", "");
                downloadButton.setAttribute("href", dataurl);
                ordnercontent.appendChild(downloadButton);
            } else {
                alert("Datei kann nicht angezeigt werden!");
            }
            
        }
    }
    request.send();
}

function getAudio(datatype, dataname) {  // funktioniert genau gleich wie Image, bloß mit Audio Element 
    var formdata = new FormData();
    formdata.append('type', 'audio')
    var request = new XMLHttpRequest();
    request.open("GET", "http://localhost:8080/" + path + "/" + dataname + "?format=base64", true);
    request.setRequestHeader("Authorization", sessionStorage.getItem("authcode"));
    request.onreadystatechange = function (event) {
        if (this.readyState == 4) {
            if (this.status == 200) {
                timer();
                document.getElementById("verzeichnis").innerText = path + "/" + dataname;
                var audio = document.createElement("audio");
                var br = document.createElement("BR");
                audio.id = "audio";
                var source = document.createElement("source");
                var dataurl = "data:" + datatype + ";base64," + this.responseText;
                source.src = dataurl;
                audio.setAttribute("controls", "");
                source.type = datatype;
                audio.appendChild(source);
                document.getElementById("ordnercontent").appendChild(audio);
                ordnercontent.appendChild(br);
                var downloadButton = document.createElement("a");
                downloadButton.innerHTML = "Download &#8595;";
                downloadButton.classList.add("downloadbutton");
                downloadButton.setAttribute("download", "");
                downloadButton.setAttribute("href", dataurl);
                ordnercontent.appendChild(downloadButton);
            } else {
                alert("Datei kann nicht angezeigt werden!");
            }
        }
    }
    request.send();
}

function getText(datatype, dataname) {  // funktioniert wie getImage(), bloß hier wird der Text durch "btoa" entschlüsselt und dann in einem pre Tag angezeigt
    var formdata = new FormData();
    formdata.append('type', 'text')
    var request = new XMLHttpRequest();
    request.open("GET", "http://localhost:8080/" + path + "/" + dataname + "?format=base64", true);
    request.setRequestHeader("Authorization", sessionStorage.getItem("authcode"));
    request.onreadystatechange = function (event) {
        if (this.readyState == 4) {
            if (this.status == 200) {
                timer();
                document.getElementById("verzeichnis").innerText = path + "/" + dataname;
                var dataurl = "data:" + datatype + ";base64," + this.responseText;
                var pre = document.createElement("pre");
                var br = document.createElement("BR");
                var downloadButton = document.createElement("a");
                pre.id = "filecontent";

                pre.innerText = atob(this.responseText);

                downloadButton.innerHTML = "Download &#8595;";
                downloadButton.classList.add("downloadbutton");
                downloadButton.setAttribute("download", "");
                downloadButton.setAttribute("href", dataurl);

                ordnercontent.appendChild(pre);
                ordnercontent.appendChild(br);
                ordnercontent.appendChild(downloadButton);
            } else {
                alert("Datei kann nicht angezeigt werden!");
            }
        }
    }
    request.send();
}


function addOrdnerElement(ordnername) {   //Funktion, die aufgerufen wird, wenn man auf Button "Ordner Hinzufügen" in Dialog klickt, um Ordner hinzuzufügen
    var type = "dir"
    var newDiv = document.createElement("div");    // wie auch in drawOrdner extra Div erstellen und type="dir" wegen Ordner setzen
    var newContent = document.createTextNode(ordnername);   // dem newDiv eine Überschrift, ein Bild und einen Delete Button mit den zugehörigen, bereits beschriebenen Funktionen geben
    var newDiv = document.createElement("div");
    var img = document.createElement("img");
    var br = document.createElement("BR");
    img.src = "/assets/ordner.png";
    var newContent = document.createElement("h1");
    newContent.innerHTML = ordnername;
    newContent.classList.add("h1");
    newDiv.appendChild(newContent);
    newDiv.appendChild(img);
    newDiv.appendChild(br);
    newDiv.id = ordnername;
    img.setAttribute("data-ordner", ordnername);
    img.setAttribute("data-type", type);
    newDiv.classList.add("daten");
    img.classList.add("img");


    var deleteButton = document.createElement("button");
    deleteButton.innerHTML = "delete " + img.getAttribute("data-ordner");  
    deleteButton.classList.add("deletebutton");
    newDiv.appendChild(deleteButton);
    deleteButton.addEventListener("click", function () {  //Falls delete Button geklickt wird, lösche dden Ordner bzw das Verzeichnis über deleteOrdner(name)
        deleteOrdner(ordnername);
    });
    img.addEventListener("click", function () {  // Falls auf das neue Ordner Icon geklickt wird, dann setze den Path neu und die History 
        path = sessionStorage.getItem("path");
        sessionStorage.setItem("path", path + "/" + this.getAttribute("data-ordner"));
        const state = { 'dir': sessionStorage.getItem("path") };
        window.history.pushState(state, sessionStorage.getItem("path"), sessionStorage.getItem("path"));
        document.getElementById("verzeichnis").innerText = sessionStorage.getItem("path") + "/";

        for (var i = 0; i < ordnercontent.childNodes.length; i++) {  // zeige anschließend nur den Inhalt (leer) von dem neu erstellten Ordner an
            if (ordnercontent.childNodes[i] == this.getAttribute("data-ordner")) {
                ordnercontent.childNodes[i].style.display = "inline";
            } else {
                ordnercontent.childNodes[i].style.display = "none";
            }
        }

    });

    document.getElementById("ordnercontent").appendChild(newDiv);  // neu erstelltes Div dem ordnercontent div hinzufügen
}




window.addEventListener("popstate", (event) => {   // Popstate Eventlistener für Navigieren mit den Browserpfeilen
    var ordner = event.state.dir;
    sessionStorage.setItem("aktuellerordner", ordner);
    getPushstateOrdner(ordner);   // Ordner in der History laden und anzeigen bei Klick auf Pfeil (gehe zu Kommentaren von getPushstateOrdner für Grund, warum nicht getOrdner genommen wurde)
    sessionStorage.setItem("path", ordner);  

    if (sessionStorage.getItem("path") == "") {  // Wenn der Path "" ist, dann kann man sich ausloggen und der Logoutbutton ist klickbar, ansonsten wenn man nicht auf der Startseite bzw im Root Verzeichnis ist, soll der Button disabled sein, sodass man sich nicht ausloggen kann
        document.getElementById("logout").disabled = "false";
        document.getElementById("logout").style.cursor = "pointer";
    }

});

function goback() {
    sessionStorage.getItem("aktuellerOrdner");  // Bei Klick auf "Zurück" Button eine Ebene zurück gehen
    window.history.go(-1);

    if (sessionStorage.getItem("path") == "") { // Wenn der Path "" ist, dann kann man sich ausloggen und der Logoutbutton ist klickbar, ansonsten wenn man nicht auf der Startseite bzw im Root Verzeichnis ist, soll der Button disabled sein, sodass man sich nicht ausloggen kann
        document.getElementById("logout").disabled = "false";
        document.getElementById("logout").style.cursor = "pointer";
    }

}
