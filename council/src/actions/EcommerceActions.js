import {
   ON_DELETE_ITEM_FROM_CART,
   ON_QUANTITY_CHANGE,
   ON_ADD_ITEM_TO_CART
} from './types';

export const deleteItemFromCart = (item) => ({
   type: ON_DELETE_ITEM_FROM_CART,
   payload: item
})

export const onChangeProductQuantity = (quantity, cartItem) => ({
   type: ON_QUANTITY_CHANGE,
   payload: { quantity, cartItem }
})

export const onAddItemToCart = (hitItem) => ({
   type: ON_ADD_ITEM_TO_CART,
   payload: hitItem
})