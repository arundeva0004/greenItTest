import { Component, OnInit,Input, Output, EventEmitter } from '@angular/core';
import {Validators, FormGroup, FormControl, FormBuilder} from "@angular/forms";
import {Order} from "../../model/Order";

@Component({
  selector: 'app-edit-order',
  templateUrl: './edit-order.component.html',
  styleUrls: ['./edit-order.component.css']
})

export class EditOrderComponent {

  constructor(
    private fb: FormBuilder,
  ) {
  }

  public editData : any;

  //ORDER FORM
  public OrderForm: FormGroup = new FormGroup({
    name: this.fb.control('',[Validators.required, Validators.pattern("^[a-zA-Z]+[a-zA-Z_\\s.]*$")]),
    item: this.fb.control("", [Validators.required, Validators.pattern("^[a-zA-Z0-9]+[a-zA-Z_\\s.]*$")]),
    amount: this.fb.control("", [Validators.required, Validators.pattern("^[0-9.]*$")]),
    qty: this.fb.control("", [Validators.required, Validators.pattern("^[0-9]*$")]),
    state: this.fb.control("", [Validators.required, Validators.pattern("^[a-zA-Z0-9]+[a-zA-Z0-9_\\s.]*$")]),
    zip: this.fb.control("", [Validators.required, Validators.pattern("^[0-9]*$")]),
  });


  @Input() public isNew = false;//CREATE OR
  @Input() public active = false;//POP-UP

  /**
   * SET ORDER DATA TO THE FORM
   * @param order
   */
  @Input() public set model(order: Order) {
    this.editData = order;
    if(!this.isNew && this.editData !== null){
      this.OrderForm.reset(order);
    } else {
      this.OrderForm.reset();
    }
    // toggle the Dialog visibility
    this.active = order !== undefined;
  }

  //EMIT THE DATA TO PARENT COMPONENT
  @Output() cancel: EventEmitter<undefined> = new EventEmitter();
  @Output() save: EventEmitter<Order> = new EventEmitter();
  @Output() update: EventEmitter<Order> = new EventEmitter();
  ngOnInit(): void {
  };

  /**
   * @desc SAVE ORDER
   * @param e
   */
  public onSave(e: PointerEvent): void {

    e.preventDefault();
    const orderInput = this.OrderForm.value;
    if(!this.isNew){
      orderInput.id = this.editData.id;
      this.update.emit(orderInput);
    } else {
      this.save.emit(orderInput);
    }
    this.OrderForm.reset();

    this.active = false;
  }

  /**
   * @desc CANCEL THE ORDER
   * @param e
   */
  public onCancel(e: PointerEvent): void {
    e.preventDefault();
    this.closeForm();
  }

  /**
   * CLOSE THE POP-UP
   */
  public closeForm(): void {
    this.active = false;
    this.cancel.emit();
  }

}
