import React, { useEffect, useState } from 'react';
import axios from 'axios';

function Foodlist() {
    const [foods, setFoods] = useState([]);
    const [searchFood, setSearchFood] = useState('');
    const getFoods = () => {
        axios.get(`http://localhost:8000/api/searchFood?name=` + searchFood)
            .then(function (response) {
                setFoods(response.data.data)
                console.log("data", response.data);
            })
            .catch(function (error) {
                console.log(error);
            })
            .then(function () {
            });
    }
    useEffect((() =>
        getFoods()
    ), [searchFood])
    console.log(foods)
    return (
        <div className='container'>
            <a href="/food/create" class="btn btn-primary">Thêm</a>
            <div className='row'>
                <h1>DANH SÁCH TẤT CẢ CÁC SẢN PHẨM</h1>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Search" value={searchFood} onChange={(e)=> setSearchFood(e.target.value)}/>
                </div>
                {foods.map((food, index) =>
                    <div key={index} className='col-3' >
                        <div className='card' style={{ width: '18rem' }}>
                            <img className='card-img-top' src={`http://localhost:8000/images/${food.image}`} />
                            <div className='card-body'>
                                <h5 className='card-title'>{food.name}</h5>
                                <p className='card-text text-truncate'>{food.description}</p>
                                <div className='row justify-content-evenly'>
                                    <p className='card-text col-6 text-success'>{food.price} VND</p>
                                    <p className='card-text col-6 text-decoration-line-through'>{food.old_price} VND</p>
                                </div>
                                <a href="/food/{food.id}" class="btn btn-primary">Chi tiết</a>
                            </div>
                        </div>
                    </div>)}
            </div>
        </div>
    )
}

export default Foodlist;