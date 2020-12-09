import { createStackNavigator } from 'react-navigation-stack';
import 'react-native-gesture-handler';
import { createAppContainer } from 'react-navigation';
import Home from '../screens/home';
import Edit from '../screens/edit';


const screens = {
    Home: {
        screen: Home
    },
    Edit: {
        screen: Edit
    }
}

const HomeStack = createStackNavigator(screens);

export default createAppContainer(HomeStack);