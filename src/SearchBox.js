import React from 'react';

const SearchBox = ({searchfield, searchChange}) =>{
  return(
    <div className="pa2">
        <input 
            type="search" 
            className="pa3 ba b--green bg-light-blue" 
            name="searchbox" 
            placeholder="Search Employee"
            onChange = {searchChange}
        />
    </div>
  )
}

export default SearchBox;