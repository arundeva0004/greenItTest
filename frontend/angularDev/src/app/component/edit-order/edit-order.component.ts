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
  public editOrder: FormGroup = new FormGroup({
    name: this.fb.control('',[Validators.required, Validators.pattern("^[a-zA-Z0-9]+[a-zA-Z0-9_\\s.]*$")]),
    item: this.fb.control("", [Validators.required, Validators.pattern("^[a-zA-Z0-9]+[a-zA-Z0-9_\\s.]*$")]),
    amount: this.fb.control("", [Validators.required, Validators.pattern("^[0-9.]*$")]),
    qty: this.fb.control("", [Validators.required, Validators.pattern("^[0-9]*$")]),
    state: this.fb.control("", [Validators.required, Validators.pattern("^[a-zA-Z0-9]+[a-zA-Z0-9_\\s.]*$")]),
    zip: this.fb.control("", [Validators.required, Validators.pattern("^[0-9]*$")]),
  });

  @Input() public isNew = false;
  @Input() public active = false;

  @Input() public set model(order: Order) {
    this.editData = order;
    if(this.editData !=  null) this.editOrder.reset(order);
    // toggle the Dialog visibility
    this.active = order !== undefined;
  }


  @Output() cancel: EventEmitter<undefined> = new EventEmitter();
  @Output() save: EventEmitter<Order> = new EventEmitter();
  @Output() update: EventEmitter<Order> = new EventEmitter();
  ngOnInit(): void {
  };

  public onSave(e: PointerEvent): void {

    e.preventDefault();
    if(this.editData != null){
      const orderInput = this.editOrder.value;
      orderInput.id = this.editData.id;
      this.update.emit(orderInput);
    } else {
      this.save.emit(this.editOrder.value);
    }

    this.active = false;
  }

  public onCancel(e: PointerEvent): void {
    e.preventDefault();
    this.closeForm();
  }

  public closeForm(): void {
    this.active = false;
    this.cancel.emit();
  }

}
