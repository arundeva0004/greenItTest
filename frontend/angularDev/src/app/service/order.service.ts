import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import { Observable } from  'rxjs';

@Injectable({
  providedIn: 'root'
})
export class OrderService {

  PHP_API_SERVER = "http://localhost/backend/app/";

  public id: number | undefined;
  constructor(private httpClient : HttpClient) { }

  /* GET ALL ORDERS FROM CSV FILE */
  getAllOrders() : Observable<any> {
    return this.httpClient.get(this.PHP_API_SERVER + 'OrderController.php',{});
  }

  /* ADD NEW ORDER */
  newOrder(data: object) {
    return this.httpClient.post(this.PHP_API_SERVER + 'OrderController.php',JSON.stringify(data));
  }

  /* UPDATE THE ORDER */
  updateOrder (data :object){
    return this.httpClient.put(this.PHP_API_SERVER + 'OrderController.php',JSON.stringify(data));
  }

  /* DELETE ORDER */
  deleteOrder(data: object) : Observable<any>{
    let body= JSON.stringify(data);
    return this.httpClient.delete(this.PHP_API_SERVER + 'OrderController.php',{body});
  }
}
