import { StatusBar } from 'expo-status-bar';
import React, { useState } from 'react';
import { StyleSheet, Text, View, Button, FlatList, TouchableOpacity, TextInput } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { AntDesign } from '@expo/vector-icons'; 

export default function AddTodo({ submitHandler }) {

    const[text, setText] = useState('');

    const changeHandler = (value) => {
        setText(value);
    }

    return(
        <View>
            <TextInput 
                style={styles.input}
                placeholder='neues Todo...'
                onChangeText={changeHandler}
            />
            <AntDesign name="pluscircleo" size={24} color="black" onPress={() => submitHandler(text)} color='coral' style={styles.add}/>
        </View>
    )
}

const styles = StyleSheet.create({
    input: {
        marginBottom: 10,
        paddingHorizontal: 8,
        paddingVertical: 6,
        borderBottomWidth: 1,
        borderBottomColor: '#ddd',
    },
    add: {
        textAlign: 'center',
    }
})