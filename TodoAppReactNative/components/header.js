import { StatusBar } from 'expo-status-bar';
import React, { useState } from 'react';
import { StyleSheet, Text, View, Button, FlatList } from 'react-native';
import { Ionicons } from '@expo/vector-icons';

export default function Header() {
    return (
        <View style={styles.header}>
            <Text style={styles.title}>Meine Todo's <Ionicons name="md-checkmark-circle" size={30} color="white" />
            
            </Text>
        </View>
    )
}

const styles = StyleSheet.create({
    header: {
        height: 140,
        backgroundColor: 'coral',
        
    },
    title: { 
        position: 'absolute', left: '50%', top: '50%',
        transform: 'translate(-50%, -50%)',
        color: '#fff',
        fontWeight: 'bold',
        fontSize: 25,
    },
});