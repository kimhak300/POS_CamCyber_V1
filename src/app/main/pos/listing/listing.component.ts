import { Component, OnInit } from '@angular/core';
import { MatDialog, MatDialogConfig } from '@angular/material/dialog';
import { SnackbarService } from 'app/shared/services/snackbar.service';
import { environment as env } from 'environments/environment';
import { PosService } from '../pos.service';
import { ViewComponent } from '../view/view.component';

import { DateAdapter, MAT_DATE_FORMATS, MAT_DATE_LOCALE } from '@angular/material/core';
import { MomentDateAdapter } from '@angular/material-moment-adapter';
import * as _moment from 'moment';
const moment = _moment;

const MY_DATE_FORMAT = {
  parse: {
    dateInput: 'DD-MMM-YYYY', // this is how your date will be parsed from Input
  },
  display: {
    dateInput: 'DD-MMM-YYYY', // this is how your date will get displayed on the Input
    monthYearLabel: 'MMM YYYY',
    dateA11yLabel: 'LL',
    monthYearA11yLabel: 'MMM YYYY'
  }
};

@Component({
  selector: 'app-listing',
  templateUrl: './listing.component.html',
  styleUrls: ['./listing.component.scss'],
  providers: [
    { provide: DateAdapter, useClass: MomentDateAdapter, deps: [MAT_DATE_LOCALE] },
    { provide: MAT_DATE_FORMATS, useValue: MY_DATE_FORMAT }
  ],
})
export class ListingComponent implements OnInit {

  public data: any[] = [];
  public canSubmit: boolean = true;
  public fileUrl = env.fileUrl;
  public discount: number = 0;
  public is_unpaid: number = 0;
  public time: string = '';
  public cashier: string = '';
  public customer: any = null;
  public type: string = '';
  constructor(
    private _posSeriice: PosService,
    private _snackBarService: SnackbarService,
    private _dialog: MatDialog
  ) { }

  ngOnInit(): void {
    let user = JSON.parse(localStorage.getItem('user'));
    this.cashier = user.name;
    console.log(this.cashier);
    this._posSeriice.read().subscribe((res: any) => {
      this.data = res;
      console.log(this.data);
    });
  }

  // =================================  >> Add to cart
  public cart: any[] = []; // An Empty Cart. 
  addToCart(incomingItem: any, qty = 0) {
    //console.log(incomingItem); 
    let isExisting: boolean = false;
    let item: any = {
      id: incomingItem.id,
      name: incomingItem.name,
      qty: qty,
      temp_qty: qty,
      unit_price: incomingItem.unit_price,
    };

    //If cart is not empty, find added item and update its new QTY. 
    if (this.cart.length > 0) {
      //console.log('Cart is not empty.'); 
      let j = 0;
      //Loop inside the cart to find existing item; 
      this.cart.forEach(cartItem => {
        //Found the existing item (compared by incoming id)
        if (cartItem['id'] == incomingItem.id) {
          isExisting = true;
          this.cart[j]['qty'] = parseInt(cartItem['qty']) + qty; //Update QTY: the existing qty + incoming qty
          this.cart[j]['temp_qty'] = parseInt(cartItem['qty']);
        }
        j++;
      })

    }

    // If the incoming item is not found, add this new item to Cart
    if (!isExisting) {
      this.cart.push(item);
    }

    //console.log(this.cart); 
    // ===>> Refresh Total Price to display in UI
    this.getTotalPrice();

  }

  // ===============================>> Get total price
  public totalPrice: number = 0;
  getTotalPrice() {
    let total = 0;
    this.cart.forEach(item => {
      total += parseInt(item.qty) * parseInt(item.unit_price);
    });
    this.totalPrice = total;
  }

  // ================================>> Sub value 
  blur(event: any, index: number = -1) {
    const tempQty = this.cart[index]['qty'];
    if (event.target.value == 0) {
      this.canSubmit = false;
    } else {
      this.canSubmit = true;
    }
    if (event.target.value > 1000) {
      event.target.value = 1000;
    }
    if (!event.target.value) {
      this.cart[index]['qty'] = tempQty;
      this.cart[index]['temp_qty'] = tempQty;
    } else {
      this.cart[index]['qty'] = parseInt(event.target.value);
      this.cart[index]['temp_qty'] = parseInt(event.target.value);
    }
    this.getTotalPrice();
  }

  // =================================>> Remove
  remove(value: any, index: number = -1) {
    if (value == 0) {
      this.canSubmit = true;
    }
    this.cart.splice(index, 1);
    this.getTotalPrice();
  }

  // ================================>> CheckOut
  public isOrderBeingMade: boolean = false;
  checkOut() {
    let cart: any = {};
    this.cart.forEach(item => {
      cart[item.id] = item.qty;
    })
    // Convert variable cart to be a json string
    let data = {
      cart: JSON.stringify(cart)
    }
    this.isOrderBeingMade = true; // Update spinner in UI
    this.time = moment().format("dddd, MMMM Do YYYY, h:mm:ss a");
    this._posSeriice.create(data).subscribe(
      //========================>> Success
      (res: any) => {
        this.isOrderBeingMade = false;
        this._snackBarService.openSnackBar(res.message, '');
        this.cart = [];
        const dialogConfig = new MatDialogConfig();
        dialogConfig.data = res.order;
        dialogConfig.width = "650px";
        this._dialog.open(ViewComponent, dialogConfig);
      },
      //========================>> Not Success
      (err: any) => {
        this.isOrderBeingMade = false;
        this._snackBarService.openSnackBar(err.error.message, 'error');
      }
    )
  }
}
