import { StatusBar } from 'expo-status-bar';
import 'react-native-gesture-handler';
import React, { useState, useEffect } from 'react';
import { StyleSheet, Text, View, Modal, TouchableHighlight, Button, FlatList, ScrollView, Alert, AlertButton, AsyncStorage, TouchableWithoutFeedback, Keyboard } from 'react-native';
import Header from '../components/header.js'; 
import TodoItem from '../components/todoitem.js';
import AddTodo from '../components/addtodo.js'; 
import { Ionicons } from '@expo/vector-icons';
import { AntDesign } from '@expo/vector-icons';
import { createStackNavigator } from 'react-navigation-stack';
import { createAppContainer } from 'react-navigation';

export default function Home( { navigation } ) {
  const [todos, setTodos] = useState([
    { text: 'Arbeiten gehen', key: '1' },
    { text: 'Schlafen', key: '2' },
    { text: 'Essen', key: '3' },
    { text: 'Arbeiten gehen', key: '4' },
    { text: 'Schlafen', key: '5' },
    { text: 'Essen', key: '6' }, 
  ])

  const [modalVisible, setModalVisible] = useState(false);

  const submitHandler = (text) => {
    
    if (text.length > 3) {
      setTodos((prevTodos) => {
        return [
          { text: text, key: Math.random().toString() },
          ...prevTodos
        ];
        
      });
    } else {
      Alert.alert('Achtung!', 'Bitte mehr als 3 Buchstaben eingeben.', [
        { text: 'Verstanden', onPress: () => console.log('Fehlermeldung geschlossen') }
      ]);
    }
  }


  const pressHandler = (key) => {
    setTodos((prevTodos) => {
        return prevTodos.filter(todo => todo.key != key);
      }); 
  }

  const gotoEdit = () => {
      navigation.push('Edit');
  }


  return (
    <TouchableWithoutFeedback onPress={() => {
      Keyboard.dismiss();
    }}>
      <View style={styles.container}>
        <Header />
        <View style={styles.content}>
        <AddTodo submitHandler={submitHandler} />
          <View style={styles.list}>
            <FlatList
              showsVerticalScrollIndicator={false}
              data={todos}
              renderItem={({ item }) => (
                <TodoItem item={item} pressHandler={pressHandler} />      
              )}   
            /> 
          </View>
        </View>
      </View>


      <Modal
        animationType="slide"
        transparent={true}
        visible={modalVisible}
        onRequestClose={() => {
          Alert.alert("Modal has been closed.");
        }}
      >
        <View style={styles.centeredView}>
          <View style={styles.modalView}>
            <Text style={styles.modalText}>Hello World!</Text>

            <TouchableHighlight
              style={{ ...styles.openButton, backgroundColor: "#2196F3" }}
              onPress={() => {
                setModalVisible(!modalVisible);
              }}
            >
              <Text style={styles.textStyle}>Hide Modal</Text>
            </TouchableHighlight>
          </View>
        </View>
      </Modal>

      <TouchableHighlight
        style={styles.openButton}
        onPress={() => {
          setModalVisible(true);
        }}
      >
        <Text style={styles.textStyle}>Show Modal</Text>
      </TouchableHighlight>
    </TouchableWithoutFeedback> 

  )
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: 'white',
  },
  content: {
    padding: 40,
    flex: 1,
  },
  list: {
    marginTop: 20,
    flex: 1,
  }
});