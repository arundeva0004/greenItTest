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
    this.orderService.getAllOrders().subscribe((data: object) => {
        this.gridData = data;
      },
      (err) => {
        this.gridData = [];
        this.notifyService.showError("Failed to load orders!!", "Order");
      });
  }


  /*@desc NEW ORDER
  * @param formInput
  * */
  newOrder(formInput : any) : void {

    let postData = formInput;
    postData.id = this.gridData.length + 1;
    this.isNew = this.active = false;
    this.orderService.newOrder(postData).subscribe( (res )  => {
        this.notifyService.showSuccess("Order added successfully", "Order");
        this.getAllOrders();
      },
      (err) => {
        this.notifyService.showError(err.error, "Order");
      }
    )

  }

  /**
   * @desc DELETE ORDER
   * @param args
   @return object
   */
  public deleteOrder(args: RemoveEvent): void {

    let postData = args.dataItem;//ASSIGN ORDER DATA
    this.orderService.deleteOrder(postData).subscribe((res) => {
        this.notifyService.showSuccess("Deleted order successfully !!", "Order");
        this.getAllOrders();
      },
      (err) => {
        this.getAllOrders();
        this.notifyService.showError("Failed to delete order!!", "Order");
      }
    )
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

    this.orderService.updateOrder(postData).subscribe( (res )  => {

        this.notifyService.showSuccess("Order updated successfully", "Order");
        this.getAllOrders();
      },
      (err) => {
        this.notifyService.showError(err.error, "Order");
      }
    )
  }

}
