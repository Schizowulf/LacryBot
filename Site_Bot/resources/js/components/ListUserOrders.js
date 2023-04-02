import React, {useState, useEffect} from 'react'
import axios from 'axios'

export default function ListUserOrders() {

	const [orders, setOrders] = useState([])
	const [formFields, addToForm] = useState({
		symbol: 'BTCUSDT',
		trigger_price: '',
		operation: 'BUY',
		quantity: '',
		stop_loss: '',
		take_profit:'',
	})

	useEffect(() => {
		axios.get(baseUrl + '/get-user-orders?currentUserId=' + currentUserId)
			.then((response) => {
				setOrders(response.data.orders)
			})
	}, [])

	function addFieldToForm(event){
		const target = event.target
		var fieldName = event.target.getAttribute('name')
		var newFormFields = { ...formFields }
		newFormFields[fieldName] = target.value

		addToForm(newFormFields)
	}

	function createOrder(event){
		event.preventDefault()

		const newOrder = {
			symbol: formFields.symbol,
			trigger_price: formFields.trigger_price,
			operation: formFields.operation,
			quantity: formFields.quantity,
			stop_loss: formFields.stop_loss,
			take_profit: formFields.take_profit,
			user_id: currentUserId
		}

		axios.post(baseUrl + '/create-order', newOrder)
		.then((response)=>{
			console.log(response)
			setOrders(response.data.orders)
		})
		.catch(response => {
			console.log('catch', response)
		})
	}

	function deleteOrder(event){
		var orderIdToDelete = event.target.getAttribute('orderidtodelete')
		axios.post(baseUrl + '/delete-order', {
			order_id: orderIdToDelete, 
			user_id: currentUserId
		})
		.then((response)=>{
			console.log(response)
			setOrders(response.data.orders)
		})
		.catch(response => {
			console.log('catch', response)
		})
	}

	return (
		<div>
			<table className="table w-50">
				<thead>
					<tr>
						<th scope="col" className="text-center">Symbol</th>
						<th scope="col" className="text-center">Price</th>
						<th scope="col" className="text-center">Operation</th>
						<th scope="col" className="text-center">Stop loss</th>
						<th scope="col" className="text-center">Stop profit</th>
						<th scope="col" className="text-center">Actions</th>
					</tr>
				</thead>
				<tbody>
					{
						orders.map(order => 
							<tr key={order.id}>
								<td scope = "row" className="text-center">{order.symbol}</td>
								<td scope = "row" className="text-center">{order.trigger_price}</td>
								<td scope = "row" className="text-center">{order.operation}</td>
								<td scope = "row" className="text-center">{order.stop_loss}</td>
								<td scope = "row" className="text-center">{order.take_profit}</td>
								<td scope = "row" className = "d-flex justify-content-around">
									<button className = "btn btn-primary">Change</button>
									<button orderidtodelete={order.id} className = "btn btn-primary ml-1" onClick={deleteOrder}>Delete</button>
								</td>
							</tr>
						)
					}
				</tbody>
			</table>
			<form className="w-50">
				<label htmlFor="symbol" className="form-label">Symbol</label>
				<select name="symbol" className='form-select mb-3' defaultValue="BTCUSDT" onChange={addFieldToForm}>
					<option value="BTCUSDT">BTCUSDT</option>
					<option value="ETHUSDT">ETHUSDT</option>
				</select>

				<label htmlFor="trigger_price" className="form-label">Triger Price</label>
				<input type="text" className="form-control mb-3" name="trigger_price" onChange={addFieldToForm} />

				<label htmlFor="operation" className="form-label">Operation</label>
				<select name="symbol" className='form-select mb-3' defaultValue="BUY" onChange={addFieldToForm}>
					<option value="BUY">BUY</option>
					<option value="SELL">SELL</option>
				</select>

				<label htmlFor="quantity" className="form-label">Quantity</label>
				<input type="text" className="form-control mb-3" name="quantity" onChange={addFieldToForm} />

				<label htmlFor="stop_loss" className="form-label">Stop Loss</label>
				<input type="text" className="form-control mb-3" name="stop_loss" onChange={addFieldToForm} />

				<label htmlFor="take_profit" className="form-label">Take Profit</label>
				<input type="text" className="form-control mb-3" name="take_profit" onChange={addFieldToForm} />

				<button type="submit" className="btn btn-primary w-100" onClick={createOrder}>Create Order</button>
			</form>
		</div>
	)
}
