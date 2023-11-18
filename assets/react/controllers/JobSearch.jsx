import React from 'react';

import {
    Button,
    ChakraProvider,
    Container,
    Heading,
    Link,
    Table,
    TableContainer,
    Tbody,
    Td,
    Th,
    Thead,
    Tr
} from '@chakra-ui/react'
import useSWR from 'swr'
import ErrorAlert from "../components/ErrorAlert";
import Loading from "../components/Loading";

export default function (props) {
    const fetcher = (...args) => fetch(...args).then(res => res.json())
    const {data, error, isLoading} = useSWR(props.path.render, fetcher)

    if (error) return <ErrorAlert/>
    if (isLoading) return <Loading/>

    // const Submit = () => {
    //     let data = {
    //         ...type.formData,
    //         ...{token: props.token},
    //     }
    //     fetch(
    //         props.path.action, {
    //             method: 'POST',
    //             headers: {'Content-Type': 'application/json'},
    //             body: JSON.stringify(data)
    //         })
    //         .then((response) => response.json())
    //         .then((responseJson) => {
    //
    //         })
    // }

    return (
        <ChakraProvider>
            <Container mt='2' mb='3'>
                <Heading size='lg'><Link href='/'>Phazz</Link></Heading>
            </Container>
            <Container>
                <Heading mb='3' size='md'>Jobs</Heading>
                <TableContainer>
                    <Table size='md'>
                        <Thead>
                            <Tr>
                                <Th>Name</Th>
                                <Th width="120px" textAlign='center'>Action</Th>
                            </Tr>
                        </Thead>
                        <Tbody>
                            {data.items.map((item, index) => {
                                return (
                                    <Tr key={index}>
                                        <Td>
                                            <Link href={item.detail_link}>{item.name}</Link>
                                        </Td>
                                        <Td textAlign='center'>
                                            <Button colorScheme='blue' as='a' mr={2}
                                                    href={item.execution_link}>Execute</Button>
                                            <Button as='a' href={item.edit_link}>Edit</Button>
                                        </Td>
                                    </Tr>
                                )
                            })}
                        </Tbody>
                    </Table>
                </TableContainer>
            </Container>
        </ChakraProvider>
    )
}
