import {Component, OnInit} from '@angular/core';
import {OrderService} from "../../service/order.service";
import {NotificationService} from "../../service/notification.service";
import {Order}  from "../../model/Order";
import {
  AddEvent,
  RemoveEvent
} from "@progress/kendo-angular-grid";

@Component({
  selector: 'app-order',
  templateUrl: './order.component.html',
  styleUrls: ['./order.component.css']
})

export class OrderComponent implements OnInit {

  public gridView: any = [];
  public gridData: any = [];
  public orderSelectedRow: any = [];
  public OrderGridFormGroup: any;
  public editOrderItem: any = null;
  public isNew: boolean = false;
  public active: boolean = false;

  /**
   * Constructor via inject the services
   * @param orderService
   * @param notifyService
   */
  constructor(private orderService: OrderService, private notifyService: NotificationService) {
  }

  ngOnInit(): void {
    this.getAllOrders(); //GET ALL ORDERS
  };

  /* GET ALL ORDERS LIST */
  getAllOrders(): void {
    this.orderService.getAllOrders().subscribe({
      next: (data: object) => {
        this.gridData = data;
      },
      error: err => {
        this.gridData = [];
        this.notifyService.showError("Failed to load orders!!", "Order");
      },
      complete: () => {

      }
    })
  }


  /*@desc NEW ORDER
  * @param formInput
  * */
  newOrder(formInput : any) : void {

    let postData = formInput;
    //get max of orderId for increment orderId
    const ids = this.gridData.map((data: Order) => {
      return data.id;
    });
    const max = Math.max(...ids);
    postData.id = max + 1;

    this.isNew = this.active = false;
    this.orderService.newOrder(postData).subscribe( {
      next: (data: any) => {
        this.notifyService.showSuccess("Order added successfully", "Order");
        this.getAllOrders();
      },
      error: err => {
        this.notifyService.showError(err.error, "Order");
      },
      complete: () => {

      }
    })
  }

  /**
   * @desc DELETE ORDER
   * @param args
   @return object
   */
  public deleteOrder(args: RemoveEvent): void {

    let postData = args.dataItem;//ASSIGN ORDER DATA
    this.orderService.deleteOrder(postData).subscribe({
      next: (data: any) => {
        this.notifyService.showSuccess("Deleted order successfully !!", "Order");
        this.getAllOrders();
      },
      error: err => {
        this.getAllOrders();
        this.notifyService.showError("Failed to delete order!!", "Order");
      },
      complete: () => {

      }
    })
  }

  /**
   * @desc ADD ORDER AND RENDER POP-UP
   * @param
   * @return object
   */
  public addOrder(): void {
    this.editOrderItem = null;
    this.isNew = true;
    this.active = true;

  }

  /**
   * DELETE ORDER AND RENDER POP-UP
   * @param order
   */
  public editOrder(order: AddEvent): void {
    this.editOrderItem = order.dataItem;//ASSIGN ORDER DATA
    this.isNew = false;
    this.active = true;
  }

  /**
   * @desc CLOSE THE POP-UP
   * @param args
   */
  public cancelOrder(args: any): void {
    this.editOrderItem = undefined;
  }

  /* @desc UPDATE THE ORDER
  *  @param orderInput
  * */
  public updateOrder(orderInput: any): void {

    let postData = orderInput;
    this.isNew = this.active = false;

    this.orderService.updateOrder(postData).subscribe({
      next: (data: any) => {
        this.notifyService.showSuccess("Order updated successfully", "Order");
        this.getAllOrders();
      },
      error: err => {
        this.notifyService.showError(err.error, "Order");
      },
      complete: () => {

      }
    })
  }

  /* @desc Delete Multiple Order
  * */
  public deleteMultipleOrder(){

      const postData = {
        selected_rows : this.orderSelectedRow,
        multiple_order_delete : true
      }

      this.orderService.deleteMultipleOrder(postData).subscribe({
          next: (data: any) => {
            this.notifyService.showSuccess("Order Deleted successfully !!", "Order");
            this.getAllOrders();
            this.orderSelectedRow = [];
          },
          error: err => {
            this.getAllOrders();
            this.notifyService.showError("Failed to delete order!!", "Order");
            this.orderSelectedRow = [];
          },
          complete: () => {

          }
        })
  }

}
