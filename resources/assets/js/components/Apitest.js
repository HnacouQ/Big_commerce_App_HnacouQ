import Axios from 'axios';
import React,{Component} from 'react';

class Test extends Component {


    constructor(props){
        super(props);
    }


    componentDidMount(){
        Axios.get('http://127.0.0.1:8000/auth/test2')
            .then(res => {
                console.log(res.data)
            });
    }
    render() { 
        return ( 
            <h1>Đây là Test cái APi</h1>
         );
    }
}
 
export default Test ;