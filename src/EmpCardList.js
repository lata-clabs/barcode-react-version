import React from 'react';
import EmployeeCard from './EmployeeCard';

const EmpCardList =({employee}) => {
    return (
        <div>
          {employee.map((user,i)=>{
            return(
                <EmployeeCard 
                    key = {i} 
                    id = {employee[i].uid} 
                    name = {employee[i].name}
                    coffee ={employee[i].cCups}
                    tea ={employee[i].tCups}
                    cCost ={employee[i].cCost}
                    tCost ={employee[i].tCost}
                    total ={employee[i].total}

                /> 
              )
            })
           }
        </div>
    );
}

export default EmpCardList;