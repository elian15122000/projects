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
            command = listener.recognize_google(voice)
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
    if 'play' and 'youtube' in command:
        song = command.replace('play', '')
        print(song)
        talk('playing' + song)
        pywhatkit.playonyt(song)

    elif 'time' in command:
        time = datetime.datetime.now().strftime('%H:%M')
        talk("Current time is " + time)

    elif 'date' in command:
        date = datetime.datetime.now()
        talk(date)

    elif 'wikipedia' in command:
        search = command.replace('wikipedia', '')
        info = wikipedia.summary(search)
        print(info)
        talk(info)

    elif 'are you single' in command:
        talk('I do not know, find it out')

    elif 'joke' in command:
        joke = pyjokes.get_joke()
        print(joke)
        talk(joke)

    else:
        talk("Please repeat, I did not understand")

while True:
    run_alexa()

run_alexa()
