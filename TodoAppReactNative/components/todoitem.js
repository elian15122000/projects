import { StatusBar } from 'expo-status-bar';
import React, { useState } from 'react';
import { StyleSheet, Text, View, Button, FlatList, TouchableOpacity } from 'react-native';
import { AntDesign } from '@expo/vector-icons'; 

export default function TodoItem({ item, pressHandler }) {
    return(
        <TouchableOpacity>
            <View style={styles.item}>
                <Text style={styles.text}>{item.text}</Text>
                <Text style={styles.icon} onPress={() => pressHandler(item.key)}><AntDesign name="delete" size={24} color="coral" style={styles.icon} /></Text>
            </View>
            
        </TouchableOpacity>
    )
}

const styles = StyleSheet.create({
    item: {
        padding: 16,
        marginTop: 16,
        borderColor: '#bbb',
        borderWidth: 1,
        borderStyle: 'dashed',
        borderRadius: 10,
    },
    icon: {
        position: 'absolute',
        right: 8,
        bottom: 5,
    }
})