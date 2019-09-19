<?php


	function remove($array, $i)
	{
		$array2 = array();
		
		for ($id = 0; $id < sizeof($array); $id++)
		{
			if ($id != $i)
			{
				array_push($array2, $array[$id]);
			}
		}
		
		return $array2;
	}

    function buy($inventory, $name, $currency, $quantity, $price, $transaction_type)
    {       
        
        
        $current_qty = 0;
        
        for ($i = 0; $i < sizeof($inventory); $i++)
        {
            $arr = $inventory[$i];
            $smb = $arr[0];
            $type = $arr[3];
            if ($smb == $name && $type == $transaction_type)
            {
                $qty = $arr[1];
                $current_qty += $qty;
            }
        }
        
        if ($current_qty < 0)
        {
			
            $profit = 0;
            $counted_qty = 0;
            
            for ($i = 0; $i < sizeof($inventory); )
            {
                $arr = $inventory[$i];                
                $smb = $arr[0];   
                $type = $arr[3];             
                
                $removed = "false";
                
				if ($smb == $name && $type == $transaction_type)
                {
                    
                    $qty = $arr[1];
                    $prc = $arr[2];
                    

                    if ($counted_qty + $qty >= $quantity)
                    {
                        $profit -= $qty * ($prc - $price);

                        $inventory = remove($inventory, $i);
                        $removed = "true";

						
                        $counted_qty += $qty;
                        
                        $rem_qty = 0;
                        
                        for ($i2 = 0; $i2 < sizeof($inventory); $i2++)
                        {
                            $arr2 = $inventory[$i2];                
                            $smb2 = $arr2[0]; 
                            $type2 = $arr2[3];
							if ($smb2 == $name && $type2 == $transaction_type)
                            {
                                $rem_qty += $arr2[1];
                            }
                        }

                        if ($rem_qty == 0)
                        {
                            $qty = $counted_qty + $quantity;
                            if ($qty > 0)
                            {
								array_push($inventory, array($smb, $qty, $price, $type, $currency));
                            }
                            break;
                        }

                    }
                    else
                    {
                        
                        $profit -= (-$quantity - $counted_qty) * ($prc - $price);
                        $qty -= (-$quantity - $counted_qty);
                        $arr[1] = $qty;
						$inventory[$i] = $arr;
                        break;
                    }
                    
                    
                }
                
                if ($removed == "false")
                {
                    $i++;
                }
            }
            
			
			if ($transaction_type == "debit")
            {
            
                for ($i = 0; $i < sizeof($inventory); $i++)
                {
                    $arr = $inventory[$i];              
                    $smb = $arr[0];        
                    if ($smb == $currency)
                    {
                        $cash_quantity = $arr[1];

                        if ($cash_quantity > 0)
                        {
							
							$cash_quantity += $quantity * $price;
							
                            $arr[1] = $cash_quantity;     
                            $inventory[$i] = $arr;


                        }
						
                        break;
                    }
                }
            
            }
			else if ($transaction_type == "credit")
            {      
                for ($i = 0; $i < sizeof($inventory); $i++)
                {
                    $arr = $inventory[$i];          
                    $smb = $arr[0];        
					if ($smb == $currency)
                    {
                        $cash_quantity = $arr[1];

                        if ($cash_quantity > 0)
                        {
                            $cash_quantity += $profit;
                            $arr[1] = $cash_quantity;     
							$inventory[$i] = $arr;


                        }

                        break;
                    }
                }
                
            }
             
            
        }
        else
        {
			
			
			if ($name == $currency)
            {
				//print "..."."array\n";
				
				//
                //inventory.add(new Object[]{name, quantity, price, transaction_type, currency});        
				
				
				
				$ar = array($name, $quantity, $price, $transaction_type, $currency);
				
				array_push($inventory,$ar);   
				
				//print "..".sizeof($inventory)."\n";
            }  
            else
            {
				if ($transaction_type == "debit")
                {                
                    for ($i = 0; $i < sizeof($inventory); $i++)
                    {
                        $arr = $inventory[$i];                
                        $smb = $arr[0];
                        $type = $arr[3];
						if ($smb == $currency && $type == $transaction_type)
                        {
                            $cash_quantity = $arr[1];

                            if ($cash_quantity > 0)
                            {
                                $cash_quantity -= $quantity * $price;
                                $arr[1] = $cash_quantity; 
								$inventory[$i] = $arr;

								array_push($inventory, array($name, $quantity, $price, $transaction_type, $currency));  

                            }

                            break;
                        }
                    }
                }
                else if ($transaction_type == "credit")
                { 
					array_push($inventory, array($name, $quantity, $price, $transaction_type, $currency));  					
                }
                
            }
            
                        
        }
        
        
        return $inventory;
        
    }
    
    
    
	//-----------------------------------------------------------------------------------------------------------------------------------------------
	
	
	
    
    
    function sell($inventory, $name, $currency, $quantity, $price, $transaction_type)
    {
             
        $current_qty = 0;
        
        for ($i = 0; $i < sizeof($inventory); $i++)
        {
            $arr = $inventory[$i];
            $smb = $arr[0];
            $type = $arr[3];
			if ($smb == $name && $type == $transaction_type)
            {
                $qty = $arr[1];
                $current_qty += $qty;
            }
        }
        
        
        if ($current_qty > 0)
        {
            $profit = 0;     
            $counted_qty = 0;

            for ($i = 0; i < sizeof($inventory); )
            {
                $arr = $inventory[$i];                
                $smb = $arr[0];     
                $type = $arr[3];  
                
                $removed = "false";
                
				if ($smb == $name && $type == $transaction_type)
                {
                    $qty = $arr[1];
                    $prc = $arr[2];
                    
                    
                    

                    if ($counted_qty + $qty <= $quantity)
                    {
                        $profit += $qty * ($price - $prc);

                        $inventory = remove($inventory, $i);                    
                        $removed = "true";

                        $counted_qty += $qty;
                        
                        $rem_qty = 0;
                        
                        for ($i2 = 0; $i2 < sizeof($inventory); $i2++)
                        {
                            $arr2 = $inventory[$i2];                
                            $smb2 = $arr2[0];    
                            $type2 = $arr2[3];
                            if ($smb2 == $name && $type2 == $transaction_type)
                            {
                                $rem_qty += $arr2[1];
                            }
                        }
                        
                        if ($rem_qty == 0)
                        {
                            $qty = $counted_qty - $quantity;
                            if ($qty < 0)
                            {
								array_push($inventory, array($smb, $qty, $price, $type, $currency));
                            }                            
                            break;
                        }

                    }
                    else
                    {
                        $profit += ($quantity - $counted_qty) * ($price - $prc);
                        $qty -= ($quantity - $counted_qty);
                        $arr[1] = $qty;
						$inventory[$i] = $arr;
                        break;
                    }
                    
                }
                
                if ($removed == "false")
                {
                    $i++;
                }
            }
        
            if ($transaction_type == "debit")
            {
            
                for ($i = 0; $i < sizeof($inventory); $i++)
                {
                    $arr = $inventory[$i];            
                    $smb = $arr[0];     
                    $type = $arr[3];

                    if ($smb == $currency)
                    {
                        $cash_quantity = $arr[1];

                        if ($cash_quantity > 0)
                        {
                            $cash_quantity += $quantity * $price;
                            $arr[1] = $cash_quantity;     
							$inventory[$i] = $arr;                     
                        }

                        break;
                    }

                }
            
            }
            else if ($transaction_type == "credit")
            {
                for ($i = 0; $i < sizeof($inventory); $i++)
                {
                    $arr = $inventory[$i];           
                    $smb = $arr[0];     

                    if ($smb == $currency)
                    {
                        $cash_quantity = $arr[1];

                        if ($cash_quantity > 0)
                        {
                            $cash_quantity += $profit;
                            $arr[1] = $cash_quantity;   
							$inventory[$i] = $arr;                 
                        }

                        break;
                    }

                }
            }
            
            
        }
        else
        {
            if ($name == $currency)
            {
				array_push($inventory, array($name, -$quantity, $price, $transaction_type, $currency));
            }
            else
            {              
                if ($transaction_type == "debit")
                {                
                    for ($i = 0; $i < sizeof($inventory); $i++)
                    {
                        $arr = $inventory[$i];             
                        $smb = $arr[0];

                        if ($smb == $currency)
                        {
                            $cash_quantity = $arr[1];                    

                            if ($cash_quantity > 0)
                            {
                                $cash_quantity += $quantity * $price;
                                $arr[1] = $cash_quantity;     
								$inventory[$i] = $arr;

								array_push($inventory, array($name, -$quantity, $price, $transaction_type, $currency));

                            }

                            break;
                        }
                    }
                }
                else if ($transaction_type == "credit")
                {
					array_push($inventory, array($name, -$quantity, $price, $transaction_type, $currency));
                }
                
                
            }
            
            
            
             
        }
                
        return $inventory;
        
    }
    
	
	
    
	//-----------------------------------------------------------------------------------------------------------------------------------------------
	
	
    
    function CalculateTotalQuantity($inventory, $name)
    {
        $result = 0;
        
        for ($i = 0; $i < sizeof($inventory); $i++)
        {
            $arr = $inventory[$i];
            $smb = $arr[0];
            if ($smb == $name)
            {
                $qty = $arr[1];
                $result += $qty;
            }
        }
        
        return $result;
    }
       
    function CalculateAverageCost($inventory, $name)
    {
        $total_quantity = 0;
        $total_cost = 0;
        
        for ($i = 0; $i < sizeof($inventory); $i++)
        {
            $arr = $inventory[$i];
            $smb = $arr[0];
            if ($smb == $name)
            {
                $qty = $arr[1];
                $prc = $arr[2];
                $total_quantity += $qty;
                $total_cost += $qty * $prc;
            }
        }
        
        $result = 0;
        
        if ($total_quantity != 0)
        {
            $result = $total_cost / $total_quantity;
        }
        
        return $result;
    }
    
    function CalculateProfit($inventory, $name, $market_price)
    {
        $result = 0;
        
        $average_cost = CalculateAverageCost($inventory, $name);
        $total_quantity = CalculateTotalQuantity($inventory, $name);       
        
        if ($total_quantity > 0)
        {
            $result = ($market_price - $average_cost) * abs($total_quantity);
        }
        else if ($total_quantity < 0)
        {
            $result = ($average_cost - $market_price) * abs($total_quantity);
        }
        
        return $result;
    }
	
	
    function GetMarketPrice($inventory_summary, $name)
    {
        $result = 0;
        
        for ($i4 = 0; $i4 < sizeof($inventory_summary); $i4++)
        {
            $arr4 = $inventory_summary[$i4];
            $symbol4 = $arr4[0];
            if ($symbol4 == $name)
            {
                $result = $arr4[3];
                break;
            }
        }
        
        return $result;
    }
	
	
	
    function CalculateInventorySummary($inventory, $inventory_summary)
    {
        
        if (sizeof($inventory_summary) > 0)
        {
            
            $i = 1;
        
            while (true)
            {
                $arr = $inventory_summary[$i];
                $symbol = $arr[0];
                
//                if (symbol.equals("cash"))
//                {
//                    i++;
//                }
//                else
                {

                    $remove_symbol = "true";

                    for ($i2 = 0; $i2 < sizeof($inventory); $i2++)
                    {
                        $arr2 = $inventory[$i2];
                        $symbol2 = $arr2[0];

                        if ($symbol2 == $symbol)
                        {
                            $remove_symbol = "false";
                            break;
                        }

                    }

                    if ($remove_symbol == "true")
                    {
                        $inventory_summary = remove($inventory_summary, $i);
                        if (sizeof($inventory_summary) == 1) break;
                    }
                    else
                    {
                        $i++;
                        if ($i >= sizeof($inventory_summary)) break;
                    }
                
                }
                
                
				if ($i >= sizeof($inventory_summary)) break;

            }

        }
        
        //print sizeof($inventory)."\n";
		
        for ($i3 = 0; $i3 < sizeof($inventory); $i3++)
        {
            $arr3 = $inventory[$i3];            
            $symbol3 = $arr3[0];
            
            $add_symbol = "true";
            
            for ($i3a = 0; $i3a < sizeof($inventory_summary); $i3a++)
            {
                $arr3a = $inventory_summary[$i3a];
                $symbol3a = $arr3a[0];
                if ($symbol3a == $symbol3)
                {
                    $add_symbol = "false";
                    $total_quantity = CalculateTotalQuantity($inventory, $symbol3);
                    $average_cost = CalculateAverageCost($inventory, $symbol3);               
					
                    $arr3a[1] = $total_quantity;
                    $arr3a[2] = $average_cost;
                    $arr3a[3] = 0;
                    $arr3a[4] = 0;
                    $inventory_summary[$i3a] = $arr3a;
                    break;
                }
            }
            
            if ($add_symbol == "true")
            {
                $arr4 = array($symbol3, 0, 0, 0, 0);                                            
                
                $total_quantity = CalculateTotalQuantity($inventory, $symbol3);
                $average_cost = CalculateAverageCost($inventory, $symbol3);
                $arr4[1] = $total_quantity;
                $arr4[2] = $average_cost;
                $arr4[3] = 0;
                $arr4[4] = 0;                
				
				array_push($inventory_summary, $arr4);    
            }
            
            
            
        }
        
        return $inventory_summary;
        
    }
	
	
	
	
	
	
    function UpdateInventorySummary($inventory, $inventory_summary, $name, $market_price)
    {
        //print "*******\n";
        
        for ($i4 = 0; $i4 < sizeof($inventory_summary); $i4++)
        {
            $arr4 = $inventory_summary[$i4];
            $symbol4 = $arr4[0];
			
			
            if ($symbol4 == $name)
            {
				
                $total_quantity = CalculateTotalQuantity($inventory, $symbol4);
                $average_cost = CalculateAverageCost($inventory, $symbol4);
                $profit = CalculateProfit($inventory, $symbol4, $market_price);
				
				//print "******* ".$total_quantity."  ".$average_cost."  ".$profit."\n";
				
                $arr4[1] = $total_quantity;
                $arr4[2] = $average_cost;
                $arr4[3] = $market_price;
                $arr4[4] = round($profit, 2);
                $inventory_summary[$i4] = $arr4;
                break;
            }
        }
        
        return $inventory_summary;
        
        
    }
	
	
    
    function PrintInventorySummary($inventory_summary)
    {
        print "inventory summary:\n";
        for ($i = 0; $i < sizeof($inventory_summary); $i++)
        {
            $arr = $inventory_summary[$i];
			
			print $arr[0]."  ".$arr[1]."  ".$arr[2]."  ".$arr[3]."  ".$arr[4]."\n";
            
            
            
        }
		
		
    }
	
	
    function CalculateBalance($inventory_summary)
    {
		$result = 0;
		
        for ($i = 0; $i < sizeof($inventory_summary); $i++)
        {
            $arr = $inventory_summary[$i];
			
			$result += $arr[1] * $arr[2];
            
            
        }
		
		return round($result, 2);
		
    }
	
	
	
    function CalculateEquity($inventory_summary)
    {
		$result = 0;
		
        for ($i = 0; $i < sizeof($inventory_summary); $i++)
        {
            $arr = $inventory_summary[$i];
			
			$result += $arr[4];
            
            
        }
		
		return round($result, 2);
		
    }
	
	
	
	
	
	
    
	//-----------------------------------------------------------------------------------------------------------------------------------------------
	
	function test1()
	{
		$inventory = array();
		$inventory_summary = array();
		
		$inventory = buy($inventory, "cad", "cad", 100000, 1, "debit");
		
		
		
		print "default\n";	
		$inventory_summary = CalculateInventorySummary($inventory, $inventory_summary);
		PrintInventorySummary($inventory_summary);
		
		print "buy\n";	
		$inventory = buy($inventory, "usd", "cad", 100000, 1.3256, "credit");	
		$inventory_summary = CalculateInventorySummary($inventory, $inventory_summary);
		PrintInventorySummary($inventory_summary);
		
		
		print "update\n";	
		$inventory_summary = UpdateInventorySummary($inventory, $inventory_summary, "usd", 1.3258);
		PrintInventorySummary($inventory_summary);

		print "sell\n";	
		$price = GetMarketPrice($inventory_summary, "usd");
		$inventory = sell($inventory, "usd", "cad", 100000, $price, "credit");	
		$inventory_summary = CalculateInventorySummary($inventory, $inventory_summary);
		PrintInventorySummary($inventory_summary);
		
		
	}
	
	
	
	function test2()
	{
		$inventory = array();
		$inventory_summary = array();
		
		$inventory = buy($inventory, "cad", "cad", 100000, 1, "debit");
		
		
		
		print "default\n";	
		$inventory_summary = CalculateInventorySummary($inventory, $inventory_summary);
		PrintInventorySummary($inventory_summary);
		
		print "sell\n";	
		$inventory = sell($inventory, "usd", "cad", 100000, 1.3256, "credit");	
		$inventory_summary = CalculateInventorySummary($inventory, $inventory_summary);
		PrintInventorySummary($inventory_summary);
		
		
		print "update\n";	
		$inventory_summary = UpdateInventorySummary($inventory, $inventory_summary, "usd", 1.3258);
		PrintInventorySummary($inventory_summary);

		print "buy\n";	
		$price = GetMarketPrice($inventory_summary, "usd");
		$inventory = buy($inventory, "usd", "cad", 100000, $price, "credit");	
		$inventory_summary = CalculateInventorySummary($inventory, $inventory_summary);
		PrintInventorySummary($inventory_summary);
		
		
	}
	
	
	function test3()
	{
		$inventory = array();
		$inventory_summary = array();
		
		$inventory = buy($inventory, "cad", "cad", 100000, 1, "debit");
		
		
		
		print "default\n";	
		$inventory_summary = CalculateInventorySummary($inventory, $inventory_summary);
		PrintInventorySummary($inventory_summary);
		
		print "buy\n";	
		$inventory = buy($inventory, "AAB", "cad", 50, 64.5, "debit");	
		$inventory_summary = CalculateInventorySummary($inventory, $inventory_summary);
		PrintInventorySummary($inventory_summary);
		
		
		print "update\n";	
		$inventory_summary = UpdateInventorySummary($inventory, $inventory_summary, "AAB", 65.3);
		PrintInventorySummary($inventory_summary);

		print "sell\n";	
		$price = GetMarketPrice($inventory_summary, "AAB");
		$quantity = CalculateTotalQuantity($inventory, "AAB");
		$inventory = sell($inventory, "AAB", "cad", $quantity, $price, "debit");	
		$inventory_summary = CalculateInventorySummary($inventory, $inventory_summary);
		PrintInventorySummary($inventory_summary);
		
		
	}
	
	
	
	function test4()
	{
		$inventory = array();
		$inventory_summary = array();
		
		$inventory = buy($inventory, "cad", "cad", 100000, 1, "debit");
		
		
		
		print "default\n";	
		$inventory_summary = CalculateInventorySummary($inventory, $inventory_summary);
		PrintInventorySummary($inventory_summary);
		
		print "sell\n";	
		$inventory = sell($inventory, "AAB", "cad", 50, 64.5, "debit");	
		$inventory_summary = CalculateInventorySummary($inventory, $inventory_summary);
		PrintInventorySummary($inventory_summary);
		
		
		print "update\n";	
		$inventory_summary = UpdateInventorySummary($inventory, $inventory_summary, "AAB", 61.3);
		PrintInventorySummary($inventory_summary);

		print "buy\n";	
		$price = GetMarketPrice($inventory_summary, "AAB");
		$quantity = CalculateTotalQuantity($inventory, "AAB");
		$inventory = buy($inventory, "AAB", "cad", $quantity, $price, "debit");	
		$inventory_summary = CalculateInventorySummary($inventory, $inventory_summary);
		PrintInventorySummary($inventory_summary);
		
		
	}
	
	//test4();
	
	
	function test5()
	{
		$inventory = array();
		$inventory_summary = array();
		
		$inventory = buy($inventory, "cad", "cad", 100000, 1, "debit");
		
		
		
		print "default\n";	
		$inventory_summary = CalculateInventorySummary($inventory, $inventory_summary);
		PrintInventorySummary($inventory_summary);
		
		print "buy\n";	
		$inventory = buy($inventory, "AAB", "cad", 50, 64.5, "debit");	
		$inventory_summary = CalculateInventorySummary($inventory, $inventory_summary);
		PrintInventorySummary($inventory_summary);
		
		print "balance:  ".CalculateBalance($inventory_summary, "cad")."\n";
		
		print "buy2\n";	
		$inventory = buy($inventory, "BBC", "cad", 50, 77.5, "debit");	
		$inventory_summary = CalculateInventorySummary($inventory, $inventory_summary);
		PrintInventorySummary($inventory_summary);
		
		print "update\n";	
		$inventory_summary = UpdateInventorySummary($inventory, $inventory_summary, "AAB", 65.3);
		PrintInventorySummary($inventory_summary);
		
		print "update2\n";	
		$inventory_summary = UpdateInventorySummary($inventory, $inventory_summary, "BBC", 79.3);
		PrintInventorySummary($inventory_summary);

		print "sell\n";	
		$price = GetMarketPrice($inventory_summary, "AAB");
		$quantity = CalculateTotalQuantity($inventory, "AAB");
		$inventory = sell($inventory, "AAB", "cad", $quantity, $price, "debit");	
		$inventory_summary = CalculateInventorySummary($inventory, $inventory_summary);
		PrintInventorySummary($inventory_summary);
		
		
		print "update3\n";	
		$inventory_summary = UpdateInventorySummary($inventory, $inventory_summary, "BBC", 83.3);
		PrintInventorySummary($inventory_summary);
		

		print "sell\n";	
		$price = GetMarketPrice($inventory_summary, "BBC");
		$quantity = CalculateTotalQuantity($inventory, "BBC");
		$inventory = sell($inventory, "BBC", "cad", $quantity, $price, "debit");	
		$inventory_summary = CalculateInventorySummary($inventory, $inventory_summary);
		PrintInventorySummary($inventory_summary);
		
	}
	
	
	function test6()
	{
		$inventory = array();
		$inventory_summary = array();
		
		$inventory = buy($inventory, "cad", "cad", 100000, 1, "debit");
		
		
		
		print "default\n";	
		$inventory_summary = CalculateInventorySummary($inventory, $inventory_summary);
		PrintInventorySummary($inventory_summary);
		
		
		print "buy\n";	
		$inventory = buy($inventory, "AAB", "cad", 50, 64.5, "debit");	
		$inventory_summary = CalculateInventorySummary($inventory, $inventory_summary);
		PrintInventorySummary($inventory_summary);
		
		
		
		print "update\n";	
		$inventory_summary = UpdateInventorySummary($inventory, $inventory_summary, "AAB", 65.3);
		PrintInventorySummary($inventory_summary);
		
		
		
		$balance = CalculateBalance($inventory_summary);
		$equity = CalculateEquity($inventory_summary);
		
		print $balance."      ".$equity."\n";
		
		$quantity = CalculateTotalQuantity($inventory, "cad");
		
		print $quantity;
		
		//print "balance:  ".CalculateBalance($inventory_summary, "cad")."\n";
	//	print "equity:  ".CalculateEquity($inventory_summary, "cad")."\n";
		
		
	}
	
	
	
	//test6();
	
	//print "done";


	
    



?>