import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import { Observable, catchError, throwError } from  'rxjs';
import {Order} from "../model/Order";
import {response} from "../model/response";


@Injectable({
  providedIn: 'root'
})
export class OrderService {

  //SERVER PATH
  PHP_API_SERVER = "http://localhost/backend/app/";


  constructor(private httpClient : HttpClient) { }

  /**
   * GET ALL ORDERS FROM CSV FILE
   * @param
   * @return array
   */
  getAllOrders() : Observable<any> {
    return this.httpClient.get(this.PHP_API_SERVER + 'OrderController.php',{}).pipe(
      catchError(this.errorHandler)
    );
  }

  /**
   * NEW ORDERS
   * @param data
   * @return object
   */
  newOrder(data: object) : Observable<response> {
    return this.httpClient.post<response>(this.PHP_API_SERVER + 'OrderController.php',JSON.stringify(data)).pipe(
      catchError(this.errorHandler)
    );
  }

  /**
   * UPDATE THE ORDER
   * @param data
   * @return object
   */
  updateOrder (data :object) : Observable<Order>{
    return this.httpClient.put<Order>(this.PHP_API_SERVER + 'OrderController.php',JSON.stringify(data)).pipe(
      catchError(this.errorHandler)
    );
  }

  /**
   * DELETE ORDER FROM A CSV FILE
   * @returns
   * @param data
   */
  deleteOrder(data: object) : Observable<response>{
    let body= JSON.stringify(data);
    return this.httpClient.delete<response>(this.PHP_API_SERVER + 'OrderController.php',{body}).pipe(
      catchError(this.errorHandler)
    );
  }

  /**
   * DELETE MULTIPLE ORDER FROM A CSV FILE
   * @returns
   * @param data
   */
  deleteMultipleOrder(data: object) : Observable<response> {
    let body= JSON.stringify(data);
    return this.httpClient.delete<response>(this.PHP_API_SERVER + 'OrderController.php',{body}).pipe(
      catchError(this.errorHandler)
    );
  }

  /**
   * Errors handler
   * function for error handling
   * @param error
   * @returns
   */
  errorHandler(error: { error: { message: string; }; status: any; message: any; }) {
    let errorMessage = '';
    if(error.error instanceof ErrorEvent) {
      //FRONT END ERROR
      errorMessage = error.error.message;
    } else {
      //SERVER ERROR
      errorMessage = `Error Code: ${error.status}\nMessage: ${error.message}`;
    }
    return throwError(() => errorMessage);
  }

}
