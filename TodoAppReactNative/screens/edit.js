import { StatusBar } from 'expo-status-bar';
import 'react-native-gesture-handler';
import React, { useState, useEffect } from 'react';
import { StyleSheet, Text, View, Button, FlatList, ScrollView, Alert, AlertButton, AsyncStorage, TouchableWithoutFeedback, Keyboard } from 'react-native';
import Header from '../components/header.js'; 
import TodoItem from '../components/todoitem.js';
import AddTodo from '../components/addtodo.js'; 
import { Ionicons } from '@expo/vector-icons';
import { AntDesign } from '@expo/vector-icons';
import { createStackNavigator } from 'react-navigation-stack';
import { createAppContainer } from 'react-navigation';

export default function Edit( { navigation } ) {


  
  

  return (
    <TouchableWithoutFeedback onPress={() => {
      Keyboard.dismiss();
    }}>
        <View>
          <AddTodo submitHandler={submitHandler} />
        </View>
    </TouchableWithoutFeedback> 
  );
} 
