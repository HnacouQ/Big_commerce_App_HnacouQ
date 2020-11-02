import React, { Component } from "react";
import Axios from "axios";

class Products extends Component {
  constructor(props) {
    super(props);
    this.state = {
      products: [],
      product: {
          id: '',
          name: '',
      }
    };
    this.handleView = this.handleView.bind(this);
    this.handleDelete = this.handleDelete.bind(this);
    this.handleUpdate = this.handleUpdate.bind(this);
    this.handleUpdate2 = this.handleUpdate2.bind(this);
  }

  componentDidMount() {
    const url = "http://127.0.0.1:8000/api/products";
    Axios.get(url).then((res) => {
      this.setState({
        products: res.data.data,
      });
    });
  }

  handleView(id){
      const url = 'http://127.0.0.1:8000/api/products';
      if(this.state.product.id === id){
         console.log('...');
      }else{
          Axios.get(url+'/'+id).then(res => {
            this.setState({
                product:{
                    id: res.data.data.id,
                    name: res.data.data.name,
                }
            })
        });

      }
      
  }

  handleDelete(id){
    const url = 'http://127.0.0.1:8000/api/products';

    Axios.delete(url+'/'+id).then(res => {
      if (res.statusText === "OK") {
        this.setState({
            products: this.state.products.filter(
                pro => pro.id !== id
            )
        });
      }
    });
  }

  handleUpdate(id){
    const url = 'http://127.0.0.1:8000/api/products';
      if(this.state.product.id === id){
         console.log('...');
      }else{
          Axios.get(url+'/'+id).then(res => {
            this.setState({
                product:{
                    id: res.data.data.id,
                    name: res.data.data.name,
                }
            })
        });

      }
  }

  handleUpdate2(id){
    const url = 'http://127.0.0.1:8000/api/products/'+id;
    
    const name = document.getElementById('name').value;
    const data = {
      name: name,
    };

    Axios.put(url,data).then(res => {
        console.log(res);
    });
  }

  componentWillUnmount() {
    this.setState({
      products: [],
    });
  }
  render() {
    console.log(this.state.product)
    const { products } = this.state;
    const { product }  = this.state;
    return (
      <div className="container">
        <table className="table table-dark container mt-4">
          <thead>
            <tr>
              <th scope="col">Name</th>
              <th scope="col">Sku</th>
              <th scope="col">Price</th>
              <th scope="col">Handle</th>
            </tr>
          </thead>
          <tbody>
            {products.map((pro) => {
              return (
                <tr key={pro.id}>
                  <th scope="col">{pro.name}</th>
                  <th scope="col">
                    {pro.sku}
                  </th>
                  <th scope="col">{pro.price}</th>
                  <th scope="col">
                    <a
                    onClick = {()=>{this.handleView(pro.id)}}
                    data-target="#staticBackdrop"
                    data-toggle="modal"
                      className="btn btn-sm btn-outline-warning mr-2"
                      
                    >
                      View
                    </a>
                    <a
                      onClick ={()=>{this.handleUpdate(pro.id)}}
                      data-target="#exampleModal"
                      data-toggle="modal"
                      className="btn btn-outline-primary btn-sm mr-2 "
                    >
                      Edit
                    </a>
                    <a onClick = {()=>{this.handleDelete(pro.id)}} className="btn btn-outline-danger btn-sm">Delete</a>
                  </th>
                </tr>
              );
            })}
          </tbody>
        </table>
        <div className="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabIndex={-1} aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div className="modal-dialog">
            <div className="modal-content">
              <div className="modal-header">
                <h5 className="modal-title" id="staticBackdropLabel">Modal title</h5>
                <button type="button" className="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div className="modal-body">
                <h1>{this.state.product.name}</h1>
              </div>
              <div className="modal-footer">
                <button type="button" className="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" className="btn btn-primary">Understood</button>
              </div>
            </div>
          </div>
        </div>
        <div className="modal fade" id="exampleModal" tabIndex={-1} aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div className="modal-dialog">
          <div className="modal-content">
            <div className="modal-header">
              <h5 className="modal-title" id="exampleModalLabel">Modal title</h5>
              <button type="button" className="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div className="modal-body">
              <label>Tên sản phẩm</label>
              <input className="form-control" id="name" value={product.name} onChange= {(e)=>{this.setState({
                product:{
                  id: this.state.product.id,
                  name: e.target.value,
                }
              })}}/>
             
            </div>
            <div className="modal-footer">
              <button type="button" className="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" onClick={()=>{this.handleUpdate2(this.state.product.id)}} className="btn btn-primary"  data-dismiss="modal">Save changes</button>
            </div>
          </div>
        </div>
      </div>
      </div>
    );
  }
}

export default Products;
