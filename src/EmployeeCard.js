import React from 'react';
import userImg from './img/user.png';

const EmployeeCard =({name,id,coffee,tea,cCost,tCost,total}) => {
    return (
      <div className="tc bg-light-green dib br3 pa3 ma2 grow bw2 shadow-5" >
          <img src={userImg} className="App-logo" alt="User" height="100hv" />
          <div>
              <h4>{id}</h4>
              <h3>{name}</h3>
              <h5>Coffee = {coffee}</h5>
              <h5>Tea = {tea}</h5>
              <h5>C-Cost = {cCost}</h5>
              <h5>T-Cost = {tCost}</h5>
              <h5>Total = {total}</h5>
          </div>
          
      </div>
    );
}

export default EmployeeCard;