<form name="formmax" method="get">
    Records per page:
    <select class="textstyle" size="1" name="max">
        <option value="1" <?php echo intval($max) == 1 ? 'selected' : ''; ?>>1</option>
        <option value="5" <?php echo intval($max) == 5 ? 'selected' : ''; ?>>5</option>
        <option value="10" <?php echo intval($max) == 10 ? 'selected' : ''; ?>>10</option>
        <option value="20" <?php echo intval($max) == 20 ? 'selected' : ''; ?>>20</option>
        <option value="30" <?php echo intval($max) == 30 ? 'selected' : ''; ?>>30</option>
        <option value="40" <?php echo intval($max) == 40 ? 'selected' : ''; ?>>40</option>
        <option value="50" <?php echo intval($max) == 50 ? 'selected' : ''; ?>>50</option>
        <option value="60" <?php echo intval($max) == 60 ? 'selected' : ''; ?>>60</option>
        <option value="70" <?php echo intval($max) == 70 ? 'selected' : ''; ?>>70</option>
        <option value="80" <?php echo intval($max) == 80 ? 'selected' : ''; ?>>80</option>
        <option value="90" <?php echo intval($max) == 90 ? 'selected' : ''; ?>>90</option>
        <option value="100" <?php echo intval($max) == 100 ? 'selected' : ''; ?>>100</option>
    </select>
</form>
