import React, {Component} from 'react';
import EmpCardList from './EmpCardList';
import employees from './api/employees.json';
import SearchBox from './SearchBox';
import './css/App.css';

class App extends Component{
  constructor(){
    super();
    this.state ={
      employees: [],
      searchfield: ''
    }
  }
  componentDidMount(){
    // fetch('http://localhost/webAppPlus11222018/barcode-react-version/src/api/emp/read.php')
    // .then(response => response.json())
    // .then(users => { this.setState({employees:users}) });.
    this.setState({employees:employees});
  }
  onSearchChange=(event)=>{
    this.setState({searchfield: event.target.value });
  }
  render(){
    const {employees,searchfield} = this.state;
    const filteredEmps= employees.filter(employee=>{
      return employee.name.toLowerCase().includes(searchfield.toLowerCase());
    })
    return(
      <div className="tc">
        <h1>Barcode App | React Version</h1>
        <SearchBox searchChange={this.onSearchChange}/>
        <EmpCardList employee = {filteredEmps}/>
      </div>
    )
  }
}

export default App;