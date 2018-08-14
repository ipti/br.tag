/**
 * Recent Orders
 */
import React, { Component } from 'react';

// api
import api from 'Api';

class RecentOrders extends Component {

	state = {
		recentOrders: null
	}

	componentDidMount() {
		this.getRecentOrders();
	}

	// recent orders
	getRecentOrders() {
		api.get('recentOrders.js')
			.then((response) => {
				this.setState({ recentOrders: response.data });
			})
			.catch(error => {
				// error hanlding
			})
	}

	render() {
		const { recentOrders } = this.state;
		return (
			<div className="table-responsive">
				<table className="table table-hover mb-0">
					<thead>
						<tr>
							<th>Order ID</th>
							<th>Invoice</th>
							<th>Customer Name</th>
							<th>Profitment</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						{recentOrders && recentOrders.map((order, key) => (
							<tr key={key}>
								<td>{order.id}</td>
								<td>{order.invoice}</td>
								<td>
									<span className="d-block fw-normal">{order.customerName}</span>
									<span className="fs-12">{order.customerEmail}</span>
								</td>
								<td>${order.amount}</td>
								<td>
									<span className={`badge ${order.labelClass}`}>{order.status}</span>
								</td>
							</tr>
						))}
					</tbody>
				</table>
			</div>
		);
	}
}

export default RecentOrders;
