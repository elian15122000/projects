import speech_recognition as sr
import pyttsx3
import pywhatkit
import datetime
import wikipedia
import pyjokes

listener = sr.Recognizer()
engine = pyttsx3.init()
voices = engine.getProperty('voices')
engine.setProperty('voice', voices[0].id)

def talk(text):
    engine.say(text)
    engine.runAndWait()


def take_command():
    try:
        with sr.Microphone() as source:
            voice = listener.listen(source)
            command = listener.recognize_google(voice, language="de-DE")
            command = command.lower()
            if 'alexa' in command:
                command = command.replace('alexa', '')
                print(command)
    except:
        pass
    return command


def run_alexa():
    command = take_command()
    print(command)
    if 'spiele' in command:
        song = command.replace('spiele', '')
        print(song)
        talk(song + 'wird auf YouTube gespielt')
        pywhatkit.playonyt(song)

    elif 'zeit' in command:
        time = datetime.datetime.now().strftime('%H:%M')
        talk("Es ist " + time)

    elif 'date' in command:
        date = datetime.datetime.now()
        talk(date)

    elif 'wikipedia' in command:
        search = command.replace('wikipedia', '')
        info = wikipedia.summary(search)
        print(info)
        talk(info)

    elif 'bist du single' in command:
        talk('Find es heraus, Süßer')

    elif 'witz' in command:
        joke = pyjokes.get_joke()
        print(joke)
        talk(joke)

    elif 'sei leise' in command:
        talk('Ich höre jetzt auf, dir zuzuhören.')
        exit()

    else:
        talk("Bitte wiederhol dich, ich habe dich nicht verstanden")

while True:
    run_alexa()

run_alexa()