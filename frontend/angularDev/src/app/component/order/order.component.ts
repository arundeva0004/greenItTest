import {Component, forwardRef, OnInit} from '@angular/core';
import {OrderService} from "../../service/order.service";
import { Validators, FormGroup, FormControl} from '@angular/forms';
import {NotificationService} from "../../service/notification.service";
import {Order}  from "../../model/Order";
import { Observable } from "rxjs";
import {
  GridComponent,
  CancelEvent,
  EditEvent,
  GridDataResult,
  AddEvent,
  RemoveEvent,
  SaveEvent
} from "@progress/kendo-angular-grid";
@Component({
  selector: 'app-dashboard',
  templateUrl: './order.component.html',
  styleUrls: ['./order.component.css']
})

export class OrderComponent implements OnInit {

  public showForm: boolean = false;
  public OrderGridForm: boolean = false;
  public gridView: any = [];
  public gridData: any = [];
  public OrderGridFormGroup: any;
  public editOrderItem: any = null;
  public isNew: boolean = false;
  public active: boolean = false;

  constructor(private orderService: OrderService, private notifyService: NotificationService) {
  }

  //ORDER FORM GROUP
  OrderForm = new FormGroup({
    id: new FormControl('', [Validators.nullValidator]),
    name: new FormControl('', [Validators.required]),
    state: new FormControl('', [Validators.required]),
    zip: new FormControl('', [Validators.required]),
    amount: new FormControl('', [Validators.required]),
    qty: new FormControl('', [Validators.required]),
    item: new FormControl('', [Validators.required])
  })

  ngOnInit(): void {
    this.getAllOrders();
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


  /*NEW ORDER */
  newOrder(formInput : any) : void {

    let postData = formInput;
    postData.id = this.gridData.length + 1;
    this.OrderForm.reset();
    this.showForm = false;
    this.orderService.newOrder(postData).subscribe( (res )  => {
        this.notifyService.showSuccess("Order added successfully", "Order");
        this.getAllOrders();
      },
      (err) => {
        this.notifyService.showError(err.error, "Order");
      }
    )

  }

  // DELETE ORDER
  public deleteOrder(args: RemoveEvent): void {

    let postData = args.dataItem;
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

  //ADD ORDER AND SHOW POP UP
  public addOrder(): void {
    this.editOrderItem = null;
    this.isNew = true;
    this.active = true;
  }

  //EDIT ORDER
  public editOrder(order: AddEvent): void {
    this.editOrderItem = order.dataItem;
    this.isNew = false;
    this.active = true;
  }

  //CANCEL ORDER
  public cancelOrder(args: any): void {
    this.editOrderItem = undefined;
  }

  /* UPDATE THE ORDER */
  public updateOrder(orderInput: any): void {

    let postData = orderInput;
    this.OrderForm.reset();
    this.isNew = this.showForm = this.active = false;

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
